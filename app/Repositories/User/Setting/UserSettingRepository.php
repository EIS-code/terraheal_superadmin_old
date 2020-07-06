<?php

namespace App\Repositories\User\Setting;

use App\Repositories\BaseRepository;
use App\UserSetting;
use App\User;
use App\UserHtmlField;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class UserSettingRepository extends BaseRepository
{
    protected $userSetting, $user, $userHtmlField;

    public function __construct()
    {
        parent::__construct();
        $this->userSetting   = new UserSetting();
        $this->user          = new User();
        $this->userHtmlField = new UserHtmlField();
    }

    public function save(array $data)
    {
        $userSetting = [];
        DB::beginTransaction();

        try {
            $userId      = (!empty($data['user_id'])) ? (int)$data['user_id'] : false;
            $settingData = [];

            if (!$userId) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "Please provide valid user id."
                ]);
            }

            $validator = $this->userSetting->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userSetting = $this->userSetting;
            $settingData = $this->getWhere('user_id', $userId);
            if (!empty($settingData) && !$settingData->isEmpty()) {
                $userSetting->where('user_id', $userId)->where('is_removed', '=', $this->userSetting::$notRemoved)->update($data);
            } else {
                $userSetting->fill($data);
                $userSetting->save();
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User setting created successfully !',
            'data' => $this->getGlobalResponse($userId)
        ]);
    }

    public function updatePassword(array $data)
    {
        DB::beginTransaction();

        try {
            $userId      = (!empty($data['user_id'])) ? (int)$data['user_id'] : false;
            $oldPassword = (!empty($data['old_password'])) ? $data['old_password'] : NULL;
            $newPassword = (!empty($data['new_password'])) ? $data['new_password'] : NULL;

            if (!$userId) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "Please provide valid user id."
                ]);
            }

            if (empty($oldPassword)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Please provide valid old password.'
                ]);
            }

            if (empty($newPassword)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Please provide valid new password.'
                ]);
            }

            if ($oldPassword === $newPassword) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "You can't use old password. Please insert new one."
                ]);
            }

            $getUser = $this->user->where('id', '=', $userId)->where('is_removed', '=', $this->user::$notRemoved)->first();

            if (empty($getUser)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "User not found."
                ]);
            }

            if (Hash::check($oldPassword, $getUser['password'])) {
                $validator = $this->user->validator(['password' => $newPassword], $userId, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->user->where('id', '=', $userId)->where('is_removed', '=', $this->user::$notRemoved)->update(['password' => Hash::make($newPassword)]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => "Old password seems wrong."
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User password updated successfully !'
        ]);
    }

    public function logout(array $data)
    {
        /* TODO: For complete token. */

        $userId = (!empty($data['user_id'])) ? (int)$data['user_id'] : false;

        $getUser = $this->user->where('id', '=', $userId)->where('is_removed', '=', $this->user::$notRemoved)->first();
        if (empty($getUser)) {
            return response()->json([
                'code' => 401,
                'msg'  => "User not found."
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User logged out successfully !'
        ]);
    }

    public function all()
    {
        return $this->userSetting->all();
    }

    public function getWhere($column, $value)
    {
        return $this->userSetting->where($column, $value)->where('is_removed', '=', $this->userSetting::$notRemoved)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userSetting->where($column, $value)->where('is_removed', '=', $this->userSetting::$notRemoved)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User setting found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $user = $this->userSetting->where('is_removed', '=', $this->userSetting::$notRemoved)->find($id);

        if (!empty($user)) {
            return $user->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }

    public function getGlobalResponse(int $id, bool $isApi = false)
    {
        $data          = $this->userSetting->where('user_id', $id)->where('is_removed', '=', $this->userSetting::$notRemoved)->first();
        $userHtmlField = $this->userHtmlField->where('is_removed', '=', $this->userHtmlField::$notRemoved)->first();

        $userSettings = $userHtmlFields = [];
        if (!empty($data)) {
            $userSettings = $data->toArray();
        }
        if (!empty($userHtmlField)) {
            $userHtmlFields = $userHtmlField->toArray();
        }

        $data = array_merge($userSettings, $userHtmlFields);

        if ($isApi === true) {
            if (!empty($data)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User setting found successfully !',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User setting not found.',
                    'data' => []
                ]);
            }
        }

        return $data;
    }
}

<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\UserEmailOtp;
use DB;

class UserEmailOtpRepository extends BaseRepository
{
    protected $userEmailOtp;
    public    $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->userEmailOtp = new UserEmailOtp();
    }

    public function validate(array $data)
    {
        $validator = $this->userEmailOtp->validator($data);
        if ($validator->fails()) {
            return ['is_validate' => 0, 'msg' => $validator->errors()->first()];
        }

        return ['is_validate' => 1, 'msg' => ''];
    }

    public function create(array $data)
    {
        $userEmailOtp = [];
        DB::beginTransaction();

        try {
            $validator = $this->userEmailOtp->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userEmailOtp = $this->userEmailOtp;
            $userEmailOtp->fill($data);
            $userEmailOtp->save();
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User email otp created successfully !',
            'data' => $userEmailOtp
        ]);
    }

    public function all()
    {
        return $this->userEmailOtp->all();
    }

    public function getWhere($column, $value)
    {
        return $this->userEmailOtp->where($column, $value)->get();
    }

    public function getWhereMany(array $where)
    {
        return $this->userEmailOtp->where($where)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userEmailOtp->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User email otp found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {
        $update = false;
        /* TODO: For check user availability. */

        DB::beginTransaction();
        try {
            $validator = $this->userEmailOtp->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $update = $this->userEmailOtp->where(['user_id' => $id])->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        if ($update) {
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg'  => 'User email otp updated successfully !'
            ]);
        } else {
            return response()->json([
                'code' => 401,
                'msg'  => 'Something went wrong.'
            ]);
        }
    }

    public function updateOtp(int $id, array $data)
    {
        $update = false;
        /* TODO: For check user availability. */
        $getOtpInfo = $this->getWhereFirst('user_id', $id);
        if (empty($getOtpInfo)) {
            $this->errorMsg[] = "We didn't send OTP for this user before.";
            return $this;
        }

        DB::beginTransaction();
        try {
            $validator = $this->userEmailOtp->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $updateData = [
                'email'       => $data['email'],
                'otp'         => $data['otp'],
                'is_send'     => (!empty($data['is_send']) ? $data['is_send'] : '0'),
                'is_verified' => (isset($data['is_verified'])) ? $data['is_verified'] : $getOtpInfo->is_verified
            ];
            $update     = $this->userEmailOtp->where(['user_id' => $id, 'email' => $getOtpInfo->email])->update($updateData);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        if ($update) {
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg'  => 'User email otp updated successfully !'
            ]);
        } else {
            return response()->json([
                'code' => 401,
                'msg'  => 'Something went wrong.'
            ]);
        }
    }

    public function setIsVerified(int $id, string $isVarified = '0')
    {
        $isVarified = (!in_array($isVarified, ['0', '1'])) ? '0' : $isVarified;

        return $this->userEmailOtp->where('id', $id)->update(['is_verified' => $isVarified]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userEmailOtp = $this->userEmailOtp->find($id);

        if (!empty($userEmailOtp)) {
            return $userEmailOtp->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }
}

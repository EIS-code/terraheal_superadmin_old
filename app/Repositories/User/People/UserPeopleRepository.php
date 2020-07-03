<?php

namespace App\Repositories\User\People;

use App\Repositories\BaseRepository;
use App\UserPeople;
use Illuminate\Http\Request;
use DB;

class UserPeopleRepository extends BaseRepository
{
    protected $userPeople;

    public function __construct()
    {
        parent::__construct();
        $this->userPeople = new UserPeople();
        $this->fileSystem = $this->userPeople->fileSystem;
        $this->photoPath  = $this->userPeople->photoPath;
    }

    public function create(Request $request)
    {
        $userPeople = [];
        DB::beginTransaction();

        try {
            $data = $request->all();

            $validator = $this->userPeople->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            if (!empty($request->photo)) {
                $validate = $this->userPeople->validatePhoto($request);
                if ($validate->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validate->errors()->first()
                    ]);
                }

                unset($data['photo']);
            }

            $userPeople = $this->userPeople;
            $userPeople->fill($data);
            $userPeople->save();
            $userPeopleId = $userPeople->id;

            if (!empty($request->photo)) {
                $fileName      = time() . '_' . $userPeopleId . '.' . $request->photo->getClientOriginalExtension();
                $storeFile     = $request->photo->storeAs($this->photoPath, $fileName, $this->fileSystem);

                $userPeople->update(['photo' => $fileName]);
            }

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User people created successfully !',
            'data' => $userPeople
        ]);
    }

    public function all()
    {
        return $this->userPeople->all();
    }

    public function getWhere($column, $value)
    {
        return $this->userPeople->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userPeople->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User peoples found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, Request $request)
    {
        $userPeople = [];
        DB::beginTransaction();

        try {
            $data = $request->all();
            if (isset($data['user_id'])) {
                unset($data['user_id']);
            }

            $validator = $this->userPeople->validator($data, true);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            if (empty($id)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "Please provide valid id."
                ]);
            }

            $getUserPeople = $this->getWhereFirst('id', $id);
            if (empty($getUserPeople)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => "Please provide valid id."
                ]);
            }

            if (!empty($request->photo)) {
                $validate = $this->userPeople->validatePhoto($request);
                if ($validate->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validate->errors()->first()
                    ]);
                }

                unset($data['photo']);
            }

            $update = $this->userPeople->where('id', $id)->update($data);

            if ($update && !empty($request->photo)) {
                $fileName      = time() . '_' . $id . '.' . $request->photo->getClientOriginalExtension();
                $storeFile     = $request->photo->storeAs($this->photoPath, $fileName, $this->fileSystem);

                $this->userPeople->where('id', $id)->update(['photo' => $fileName]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        if ($update) {
            DB::commit();
            $userPeople = $this->getWhereFirst('id', $id);
            return response()->json([
                'code' => 200,
                'msg'  => 'User people updated successfully !',
                'data' => $userPeople
            ]);
        } else {
            return response()->json([
                'code' => 401,
                'msg'  => 'Something went wrong.'
            ]);
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'User people not found.'
        ]);
    }

    public function remove(int $id, $isApi = false)
    {
        if (!empty($id)) {
            $userPeople = $this->userPeople->find($id);

            if (!empty($userPeople)) {
                $userPeople->is_removed = $this->userPeople::$removed;

                if ($userPeople->save()) {
                    return response()->json([
                        'code' => 200,
                        'msg'  => 'User people removed successfully.'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'User people not found.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'User people not removed.'
        ]);
    }

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $data = $this->userPeople->where('id', $id)->where('is_removed', $this->userPeople::$notRemoved);

        if (!empty($data)) {
            return $data->get();
        }

        return NULL;
    }

    public function getByUserId(int $id, $isApi = false)
    {
        $userPeople = $this->userPeople->where('user_id', $id)->where('is_removed', $this->userPeople::$notRemoved)->get();

        if (!empty($userPeople) && !$userPeople->isEmpty()) {
            if ($isApi === true) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User people found successfully.',
                    'data' => $userPeople
                ]);
            }

            return $userPeople;
        }

        if ($isApi === true) {
            return response()->json([
                'code' => 401,
                'msg'  => 'User people not found.',
                'data' => $userPeople
            ]);
        }

        return $userPeople;
    }

    public function errors()
    {}
}

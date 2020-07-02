<?php

namespace App\Repositories\User\Address;

use App\Repositories\BaseRepository;
use App\UserAddress;
use Carbon\Carbon;
use DB;

class UserAddressRepository extends BaseRepository
{
    protected $userAddress;

    public function __construct()
    {
        parent::__construct();
        $this->userAddress = new UserAddress();
    }

    public function create(array $data)
    {
        $userAddress = [];
        DB::beginTransaction();

        try {
            $validator = $this->userAddress->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userAddress = $this->userAddress;
            $userAddress->fill($data);
            $userAddress->save();
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User address created successfully !',
            'data' => $userAddress
        ]);
    }

    public function all()
    {
        return $this->userAddress->all();
    }

    public function getWhere($column, $value)
    {
        return $this->userAddress->where($column, $value)->where('is_removed', $this->userAddress::$notRemoved)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userAddress->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User address found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(array $data)
    {
        if (isset($data['user_id'])) {
            unset($data['user_id']);
        }

        $id              = (!empty($data['id'])) ? (int)$data['id'] : false;
        $update          = false;
        $findUserAddress = $this->get($id);

        if (!empty($findUserAddress) && !$findUserAddress->isEmpty()) {
            DB::beginTransaction();

            try {
                $validator = $this->userAddress->validator($data, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->userAddress->where('id', $id)->update($data);
            } catch(Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User address updated successfully !'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Something went wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'User address not found.'
        ]);
    }

    public function remove(int $id)
    {
        if (!empty($id)) {
            $userAddress = $this->userAddress->find($id);

            if (!empty($userAddress)) {
                $userAddress->is_removed = $this->userAddress::$removed;

                if ($userAddress->save()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'User address removed successfully.'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'User address not found.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'User address not removed.'
        ]);
    }

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userAddress = $this->userAddress->find($id);

        if (!empty($userAddress)) {
            return $userAddress->where('is_removed', $this->userAddress::$notRemoved)->get();
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

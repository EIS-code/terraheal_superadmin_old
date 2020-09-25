<?php

namespace App\Repositories\User\Pack;

use App\Repositories\BaseRepository;
use App\UserPackOrder;
use DB;

class UserPackOrderRepository extends BaseRepository
{
    protected $userPackOrder;

    public function __construct()
    {
        parent::__construct();
        $this->userPackOrder = new UserPackOrder();
    }

    public function create(array $data)
    {
        $userPackOrder = [];
        DB::beginTransaction();

        try {
            $validator = $this->userPackOrder->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userPackOrder = $this->userPackOrder;
            $userPackOrder->fill($data);
            $userPackOrder->save();
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User pack ordered successfully !',
            'data' => $userPackOrder
        ]);
    }

    public function all()
    {
        return $this->userPackOrder->all();
    }

    public function getWhere($column, $value, $isApi = false)
    {
        $data = $this->userPackOrder->where($column, $value)->get();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User pack orders found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User pack order not found !',
                'data' => []
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
        $userPackOrder = $this->userPackOrder->find($id);

        if (!empty($userPackOrder)) {
            return $userPackOrder->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

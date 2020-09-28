<?php

namespace App\Repositories\User\Pack;

use App\Repositories\BaseRepository;
use App\UserPack;
use App\UserPackOrder;
use DB;

class UserPackRepository extends BaseRepository
{
    protected $userPack, $userPackOrder;

    public function __construct()
    {
        parent::__construct();
        $this->userPack      = new UserPack();
        $this->userPackOrder = new UserPackOrder();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->userPack->all();
    }

    public function getWhere($column, $value, $isApi = false)
    {
        $data = $this->userPack->where($column, $value)->get();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User packs found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User pack not found !',
                'data' => []
            ]);
        }

        return $data;
    }

    public function getPacks(array $data)
    {
        if (!empty($data['shop_id'])) {
            $shopId = (int)$data['shop_id'];

            return $this->getWhere('shop_id', $shopId, true);
        } elseif (!empty($data['user_id'])) {
            $return = [];
            $userId = (int)$data['user_id'];

            $getPacks = $this->userPackOrder->with('userPack')->where('user_id', $userId)->get();

            if (!empty($getPacks) && !$getPacks->isEmpty()) {
                $getPacks->map(function($userPack) use(&$return) {
                    $return[] = $userPack->userPack;
                });
            }

            if (!empty($return)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User packs found successfully !',
                    'data' => $return
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User pack not found !',
            'data' => []
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userPack = $this->userPack->find($id);

        if (!empty($userPack)) {
            return $userPack->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

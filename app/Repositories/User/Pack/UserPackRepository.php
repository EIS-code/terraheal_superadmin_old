<?php

namespace App\Repositories\User\Pack;

use App\Repositories\BaseRepository;
use App\UserPack;
use DB;

class UserPackRepository extends BaseRepository
{
    protected $userPack;

    public function __construct()
    {
        parent::__construct();
        $this->userPack = new UserPack();
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

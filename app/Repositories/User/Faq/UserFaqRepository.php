<?php

namespace App\Repositories\User\Faq;

use App\Repositories\BaseRepository;
use App\UserFaq;
use DB;

class UserFaqRepository extends BaseRepository
{
    protected $userFaq;

    public function __construct()
    {
        parent::__construct();
        $this->userFaq = new UserFaq();
    }

    public function create(array $data)
    {}

    public function all($isApi = false)
    {
        $data = $this->userFaq->all();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User FAQ found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User FAQ not found !',
                'data' => []
            ]);
        }

        return $data;
    }

    public function getWhere($column, $value)
    {
        return $this->userFaq->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $userFaq = $this->userFaq->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User FAQ found successfully !',
                'data' => $userFaq
            ]);
        }

        return $userFaq;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userFaq = $this->userFaq->find($id);

        if (!empty($userFaq)) {
            return $userFaq->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

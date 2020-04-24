<?php

namespace App\Repositories\Massage;

use App\Repositories\BaseRepository;
use App\Massage;
use DB;

class MassageRepository extends BaseRepository
{
    protected $massage;

    public function __construct()
    {
        parent::__construct();
        $this->massage = new Massage();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->massage->all();
    }

    public function getWhere($column, $value)
    {
        return $this->massage->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->massage->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $massage = $this->massage->find($id);

        if (!empty($massage)) {
            return $massage->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

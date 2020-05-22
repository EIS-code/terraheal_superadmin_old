<?php

namespace App\Repositories\Therapy;

use App\Repositories\BaseRepository;
use App\Therapy;
use DB;

class TherapyRepository extends BaseRepository
{
    protected $therapy;

    public function __construct()
    {
        parent::__construct();
        $this->therapy = new Therapy();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->therapy->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapy->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapy->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapy found successfully !',
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

    public function get(array $data, int $limit = 10)
    {
        $query = (!empty($data['q'])) ? $data['q'] : NULL;
        $limit = (!is_numeric($limit)) ? 10 : $limit;

        $getTherapies = $this->therapy->where("name", "LIKE", "%{$query}%")->limit($limit)->get();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapy found successfully !',
            'data' => $getTherapies
        ]);
    }

    public function errors()
    {}
}

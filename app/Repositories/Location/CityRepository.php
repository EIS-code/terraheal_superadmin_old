<?php

namespace App\Repositories\Location;

use App\Repositories\BaseRepository;
use App\City;
use DB;

class CityRepository extends BaseRepository
{
    protected $city;

    public function __construct()
    {
        parent::__construct();
        $this->city = new City();
    }

    public function create(array $data)
    {}

    public function all()
    {
        /* TODO : To set limit. */
        return $this->city->all();
    }

    public function getWhere($column, $value)
    {
        return $this->city->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $cityData = $this->city->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'City found successfully !',
                'data' => $cityData
            ]);
        }

        return $cityData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $city = $this->city->find($id);

        if (!empty($city)) {
            return $city->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

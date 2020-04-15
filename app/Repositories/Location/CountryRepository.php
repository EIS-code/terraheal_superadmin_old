<?php

namespace App\Repositories\Location;

use App\Repositories\BaseRepository;
use App\Country;
use DB;

class CountryRepository extends BaseRepository
{
    protected $country;

    public function __construct()
    {
        parent::__construct();
        $this->country = new Country();
    }

    public function create(array $data)
    {}

    public function all()
    {
        /* TODO : To set limit. */
        return $this->country->all();
    }

    public function getWhere($column, $value)
    {
        return $this->country->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $countryData = $this->country->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Country found successfully !',
                'data' => $countryData
            ]);
        }

        return $countryData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $country = $this->country->find($id);

        if (!empty($country)) {
            return $country->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

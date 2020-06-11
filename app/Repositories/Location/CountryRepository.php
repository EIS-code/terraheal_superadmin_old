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

    public function createMultiple(array $data)
    {
        $country = [];
        DB::beginTransaction();

        try {
            $validator = $this->country->validatorMultiple($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $country = $this->country;
            $country->insert($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Countries created successfully !'
        ]);
    }

    public function all($isApi = false)
    {
        /* TODO : To set limit. */
        $data = $this->country->all();

        if ($isApi === true) {
            if (!empty($data)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Countries get successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Didn\'t found any country.',
                'data' => []
            ]);
        }

        return $data;
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

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

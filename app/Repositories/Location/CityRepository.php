<?php

namespace App\Repositories\Location;

use App\Repositories\BaseRepository;
use App\Country;
use App\Province;
use App\City;
use DB;

class CityRepository extends BaseRepository
{
    protected $city, $province, $country;

    public function __construct()
    {
        parent::__construct();
        $this->country  = new Country();
        $this->province = new Province();
        $this->city     = new City();
    }

    public function create(array $data)
    {}

    public function createMultiple(array $data)
    {
        $cities = [];
        DB::beginTransaction();

        try {
            $validator = $this->city->validatorMultiple($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $maxRecords = 1000;
            $cities     = $this->city;
            foreach (array_chunk($data, $maxRecords) AS $junk => $dataArray) {
                if (count($dataArray) < $maxRecords) { break; }

                $cities->insert($dataArray);
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Cities created successfully !'
        ]);
    }

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

    public function getCity($data)
    {
        $provinceId = (!empty($data['province_id'])) ? $data['province_id'] : NULL;
        $countryId  = (!empty($data['country_id'])) ? $data['country_id'] : NULL;
        $returnData = NULL;

        if (!empty($provinceId)) {
            $getData = $this->province->where('id', $provinceId)->with('city')->first();

            if (!empty($getData->city)) {
                $returnData = $getData->city;
            }
        } elseif (!empty($countryId)) {
            $getData = $this->country->where('id', $countryId)->with('province')->first();

            if (!empty($getData->province)) {
                foreach ($getData->province as $province) {
                    $returnData[] = $province->city->all();
                }
            }
        }

        if (!empty($returnData)) {
            return response()->json([
                'code' => 200,
                'msg'  => 'City found successfully !',
                'data' => $returnData
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'City doesn\'t found !',
            'data' => []
        ]);
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

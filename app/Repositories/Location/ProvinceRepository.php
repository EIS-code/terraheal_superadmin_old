<?php

namespace App\Repositories\Location;

use App\Repositories\BaseRepository;
use App\Province;
use DB;

class ProvinceRepository extends BaseRepository
{
    protected $province;

    public function __construct()
    {
        parent::__construct();
        $this->province = new Province();
    }

    public function create(array $data)
    {}

    public function createMultiple(array $data)
    {
        $province = [];
        DB::beginTransaction();

        try {
            $validator = $this->province->validatorMultiple($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $province = $this->province;
            $province->insert($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Province created successfully !'
        ]);
    }

    public function all($isApi = false)
    {
        /* TODO : To set limit. */
        $data = $this->province->all();

        if ($isApi === true) {
            if (!empty($data)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Provinces get successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Didn\'t found any province.',
                'data' => []
            ]);
        }

        return $data;
    }

    public function getWhere($column, $value, $isApi = false)
    {
        $data = $this->province->where($column, $value)->get();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Provinces get successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Didn\'t found any province.',
                'data' => []
            ]);
        }

        return $data;
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $provinceData = $this->province->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Province found successfully !',
                'data' => $provinceData
            ]);
        }

        return $provinceData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $province = $this->province->find($id);

        if (!empty($province)) {
            return $province->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

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

    public function all()
    {
        /* TODO : To set limit. */
        return $this->province->all();
    }

    public function getWhere($column, $value)
    {
        return $this->province->where($column, $value)->get();
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

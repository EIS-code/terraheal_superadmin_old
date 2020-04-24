<?php

namespace App\Repositories\Massage;

use App\Repositories\BaseRepository;
use App\MassagePrice;
use DB;

class MassagePriceRepository extends BaseRepository
{
    protected $massagePrice;

    public function __construct()
    {
        parent::__construct();
        $this->massagePrice = new MassagePrice();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->massagePrice->all();
    }

    public function getWhere($column, $value)
    {
        return $this->massagePrice->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->massagePrice->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage price found successfully !',
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
        $massagePrice = $this->massagePrice->find($id);

        if (!empty($massagePrice)) {
            return $massagePrice->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

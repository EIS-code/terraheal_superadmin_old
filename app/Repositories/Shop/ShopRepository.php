<?php

namespace App\Repositories\Shop;

use App\Repositories\BaseRepository;
use App\Shop;
use Illuminate\Support\Facades\Hash;
use DB;

class ShopRepository extends BaseRepository
{
    protected $shop;

    public function __construct()
    {
        parent::__construct();
        $this->shop = new Shop();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->shop->all();
    }

    public function getWhere($column, $value)
    {
        return $this->shop->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->shop->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Shop found successfully !',
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
        $shop = $this->shop->find($id);

        if (!empty($shop)) {
            return $shop->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

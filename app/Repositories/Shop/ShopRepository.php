<?php

namespace App\Repositories\Shop;

use App\Repositories\BaseRepository;
use App\Shop;
use App\Massage;
use Illuminate\Support\Facades\Hash;
use DB;

class ShopRepository extends BaseRepository
{
    protected $shop, $massage;

    public function __construct()
    {
        parent::__construct();
        $this->shop    = new Shop();
        $this->massage = new Massage();
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

    public function filter(array $data)
    {
        $isFiltered  = false;

        if (count($data) > 0) {
            $isFiltered = (!empty(array_filter($data)));
        }

        $shops = $this->shop::query();

        if ($isFiltered) {

            if (!empty($data['s'])) {
                $s = (string)$data['s'];

                $shops->where($this->shop::getTableName() . '.name', 'LIKE', "%$s%");
            }

            if (!empty($data['c'])) {
                $c = (int)$data['c'];

                $shops->where($this->massage::getTableName() . '.id', 'LIKE', "%$c%");
            }
        }

        $shops = $shops->select(DB::RAW($this->shop::getTableName() . '.*, COUNT(' . $this->massage::getTableName() . '.id) as totalServices'))
                       ->leftJoin($this->massage::getTableName(), $this->shop::getTableName() . '.id', '=', $this->massage::getTableName() . '.shop_id')
                       ->groupBy($this->massage::getTableName() . '.shop_id')
                       ->paginate($this::PAGINATE_RECORDS);

        return $shops;
    }

    public function getMassages()
    {
        return $this->massage::all();
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

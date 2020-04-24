<?php

namespace App\Repositories\Massage;

use App\Repositories\BaseRepository;
use App\MassageTiming;
use DB;

class MassageTimingRepository extends BaseRepository
{
    protected $massageTiming;

    public function __construct()
    {
        parent::__construct();
        $this->massageTiming = new MassageTiming();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->massageTiming->all();
    }

    public function getWhere($column, $value)
    {
        return $this->massageTiming->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->massageTiming->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage timing found successfully !',
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
        $massageTiming = $this->massageTiming->find($id);

        if (!empty($massageTiming)) {
            return $massageTiming->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

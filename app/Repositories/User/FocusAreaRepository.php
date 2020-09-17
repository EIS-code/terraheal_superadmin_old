<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\FocusArea;
use DB;

class FocusAreaRepository extends BaseRepository
{
    protected $focusArea;

    public function __construct()
    {
        parent::__construct();
        $this->focusArea = new FocusArea();
    }

    public function create(array $data)
    {}

    public function all($isApi = false)
    {
        $focusAreas = $this->focusArea->all();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Focus area found successfully !',
                'data' => $focusAreas
            ]);
        }

        return $focusAreas;
    }

    public function getWhere($column, $value)
    {
        return $this->focusArea->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->focusArea->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Focus area found successfully !',
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
        $focusArea = $this->focusArea->find($id);

        if (!empty($focusArea)) {
            return $focusArea->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }
}

<?php

namespace App\Repositories\Staff;

use App\Repositories\BaseRepository;
use App\Staff;
use DB;

class StaffRepository extends BaseRepository
{
    protected $staff;

    public function __construct()
    {
        parent::__construct();
        $this->staff = new Staff();
    }

    public function create(array $data)
    {
        $staff = [];
        DB::beginTransaction();

        try {
            $validator = $this->staff->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $staff = $this->staff;
            $staff->fill($data);
            $staff->save();
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Staff created successfully !',
            'data' => $staff
        ]);
    }

    public function all()
    {
        return $this->staff->all();
    }

    public function getWhere($column, $value)
    {
        return $this->staff->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->staff->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Staff found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {
        $update   = false;
        $findStaff = $this->get($id);

        if (!empty($findStaff) && !$findStaff->isEmpty()) {
            DB::beginTransaction();

            try {
                $validator = $this->staff->validator($data, $id, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->staff->where('id', $id)->update($data);
            } catch(Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Staff updated successfully !'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Something went wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Staff not found.'
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $staff = $this->staff->find($id);

        if (!empty($staff)) {
            return $staff->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

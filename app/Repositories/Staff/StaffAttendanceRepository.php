<?php

namespace App\Repositories\Staff;

use App\Repositories\BaseRepository;
use App\StaffAttendance;
use Carbon\Carbon;
use DB;

class StaffAttendanceRepository extends BaseRepository
{
    protected $staffAttendance;

    public function __construct()
    {
        parent::__construct();
        $this->staffAttendance = new StaffAttendance();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->staffAttendance->all();
    }

    public function getWhere($column, $value)
    {
        return $this->staffAttendance->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->staffAttendance->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Staff attendance found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function attendanceIn(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $data['staff_id'] = $id;
            $validator = $this->staffAttendance->validator($data, 'in');
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            // Check if already exists.
            $getAttendance = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->get();
            if (!empty($getAttendance) && !$getAttendance->isEmpty()) {
                $isAdded = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->update($data);
            } else {
                $now                = Carbon::now();
                $data['end_time']   = NULL;
                $data['break_from'] = NULL;
                $data['break_to']   = NULL;
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
                $isAdded            = $this->staffAttendance->insert($data);
            }

            if (!$isAdded) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Something went wrong. Please try again or contact supervisor.'
                ]);
            }
        } catch (Exception $e) {
             DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Staff in successfully !',
            'data' => $data
        ]);
    }

    public function attendanceOut(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $data['staff_id'] = $id;
            $validator = $this->staffAttendance->validator($data, 'out');
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            // Check if already exists.
            $getAttendance = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->get();
            if (!empty($getAttendance) && !$getAttendance->isEmpty()) {
                $isAdded = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->update($data);

                if (!$isAdded) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Something went wrong. Please try again or contact supervisor.'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'In time not started yet.'
                ]);
            }
        } catch (Exception $e) {
             DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Staff out successfully !',
            'data' => $data
        ]);
    }

    public function attendanceBreakIn(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $data['staff_id'] = $id;
            $validator = $this->staffAttendance->validator($data, 'breakIn');
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            // Check if already exists.
            $getAttendance = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->get();
            if (!empty($getAttendance) && !$getAttendance->isEmpty()) {
                $isAdded = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->update($data);

                if (!$isAdded) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Something went wrong. Please try again or contact supervisor.'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'In time not started yet.'
                ]);
            }
        } catch (Exception $e) {
             DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Staff break added successfully !',
            'data' => $data
        ]);
    }

    public function attendanceBreakOut(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $data['staff_id'] = $id;
            $validator = $this->staffAttendance->validator($data, 'breakOut');
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            // Check if already exists.
            $getAttendance = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->get();
            if (!empty($getAttendance) && !$getAttendance->isEmpty()) {
                $isAdded = $this->staffAttendance->where("staff_id", "=", $id)->whereRaw("DATE(date) = '{$data['date']}'")->update($data);

                if (!$isAdded) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Something went wrong. Please try again or contact supervisor.'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'In time not started yet.'
                ]);
            }
        } catch (Exception $e) {
             DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Staff break over successfully !',
            'data' => $data
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $staffAttendance = $this->staffAttendance->find($id);

        if (!empty($staffAttendance)) {
            return $staffAttendance->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

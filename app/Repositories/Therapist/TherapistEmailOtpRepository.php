<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistEmailOtp;
use DB;

class TherapistEmailOtpRepository extends BaseRepository
{
    protected $therapistEmailOtp;
    public    $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapistEmailOtp = new TherapistEmailOtp();
    }

    public function validate(array $data)
    {
        $validator = $this->therapistEmailOtp->validator($data);
        if ($validator->fails()) {
            return ['is_validate' => 0, 'msg' => $validator->errors()->first()];
        }

        return ['is_validate' => 1, 'msg' => ''];
    }

    public function create(array $data)
    {
        $therapistEmailOtp = [];
        DB::beginTransaction();

        try {
            $validator = $this->therapistEmailOtp->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $therapistEmailOtp = $this->therapistEmailOtp;
            $therapistEmailOtp->fill($data);
            $therapistEmailOtp->save();
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist email otp created successfully !',
            'data' => $therapistEmailOtp
        ]);
    }

    public function all()
    {
        return $this->therapistEmailOtp->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistEmailOtp->where($column, $value)->get();
    }

    public function getWhereMany(array $where)
    {
        return $this->therapistEmailOtp->where($where)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistEmailOtp->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist email otp found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {
        $update = false;
        /* TODO: For check therapist availability. */

        DB::beginTransaction();
        try {
            $validator = $this->therapistEmailOtp->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $update = $this->therapistEmailOtp->where(['therapist_id' => $id, 'email' => $data['email']])->update(['otp' => $data['otp']]);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        if ($update) {
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist email otp updated successfully !'
            ]);
        } else {
            return response()->json([
                'code' => 401,
                'msg'  => 'Something went wrong.'
            ]);
        }
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapistEmailOtp = $this->therapistEmailOtp->find($id);

        if (!empty($therapistEmailOtp)) {
            return $therapistEmailOtp->get();
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

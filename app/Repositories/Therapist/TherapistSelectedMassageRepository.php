<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistSelectedMassage;
use DB;

class TherapistSelectedMassageRepository extends BaseRepository
{
    protected $therapistSelectedMassage;
    public    $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapistSelectedMassage = new TherapistSelectedMassage();
    }

    public function validate(array $data)
    {
        $validator = $this->therapistSelectedMassage->validator($data);
        if ($validator->fails()) {
            return ['is_validate' => 0, 'msg' => $validator->errors()->first()];
        }

        return ['is_validate' => 1, 'msg' => ''];
    }

    public function create(array $data, bool $isExcludeValidation = false)
    {
        $therapistSelectedMassage = [];
        DB::beginTransaction();

        try {
            if (!$isExcludeValidation) {
                $validator = $this->therapistSelectedMassage->validator($data);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }
            }

            $therapistSelectedMassage = $this->therapistSelectedMassage;
            // $therapistSelectedMassage->fill($data);
            $therapistSelectedMassage->insert($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist email otp created successfully !',
            'data' => $therapistSelectedMassage
        ]);
    }

    public function all()
    {
        return $this->therapistSelectedMassage->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistSelectedMassage->where($column, $value)->get();
    }

    public function getWhereMany(array $where)
    {
        return $this->therapistSelectedMassage->where($where)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistSelectedMassage->where($column, $value)->first();

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
            $validator = $this->therapistSelectedMassage->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $update = $this->therapistSelectedMassage->where(['therapist_id' => $id])->update($data);
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

    public function updateOtp(int $id, array $data)
    {
        $update = false;
        /* TODO: For check therapist availability. */
        $getOtpInfo = $this->getWhereFirst('therapist_id', $id);
        if (empty($getOtpInfo)) {
            $this->errorMsg[] = "We didn't send OTP for this therapist before.";
            return $this;
        }

        DB::beginTransaction();
        try {
            $validator = $this->therapistSelectedMassage->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $updateData = [
                'email'       => $data['email'],
                'otp'         => $data['otp'],
                'is_send'     => (!empty($data['is_send']) ? $data['is_send'] : '0'),
                'is_verified' => (isset($data['is_verified'])) ? $data['is_verified'] : $getOtpInfo->is_verified
            ];
            $update     = $this->therapistSelectedMassage->where(['therapist_id' => $id, 'email' => $getOtpInfo->email])->update($updateData);
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

    public function setIsVerified(int $id, string $isVarified = '0')
    {
        $isVarified = (!in_array($isVarified, ['0', '1'])) ? '0' : $isVarified;

        return $this->therapistSelectedMassage->where('id', $id)->update(['is_verified' => $isVarified]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapistSelectedMassage = $this->therapistSelectedMassage->find($id);

        if (!empty($therapistSelectedMassage)) {
            return $therapistSelectedMassage->get();
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

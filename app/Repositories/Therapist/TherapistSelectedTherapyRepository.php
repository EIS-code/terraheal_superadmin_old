<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistSelectedTherapy;
use DB;

class TherapistSelectedTherapyRepository extends BaseRepository
{
    protected $therapistSelectedTherapy;
    public    $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapistSelectedTherapy = new TherapistSelectedTherapy();
    }

    public function validate(array $data)
    {
        $validator = $this->therapistSelectedTherapy->validator($data);
        if ($validator->fails()) {
            return ['is_validate' => 0, 'msg' => $validator->errors()->first()];
        }

        return ['is_validate' => 1, 'msg' => ''];
    }

    public function create(array $data, bool $isExcludeValidation = false)
    {
        $therapistSelectedTherapy = [];
        DB::beginTransaction();

        try {
            if (!$isExcludeValidation) {
                $validator = $this->therapistSelectedTherapy->validator($data);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }
            }

            $therapistSelectedTherapy = $this->therapistSelectedTherapy;
            // $therapistSelectedTherapy->fill($data);
            $therapistSelectedTherapy->insert($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist therapy created successfully !',
            'data' => $therapistSelectedTherapy
        ]);
    }

    public function all()
    {
        return $this->therapistSelectedTherapy->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistSelectedTherapy->where($column, $value)->get();
    }

    public function getWhereMany(array $where)
    {
        return $this->therapistSelectedTherapy->where($where)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistSelectedTherapy->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist therapy found successfully !',
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
            $validator = $this->therapistSelectedTherapy->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $update = $this->therapistSelectedTherapy->where(['id' => $id])->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        if ($update) {
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist therapy updated successfully !'
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
        $therapistSelectedTherapy = $this->therapistSelectedTherapy->find($id);

        if (!empty($therapistSelectedTherapy)) {
            return $therapistSelectedTherapy->get();
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

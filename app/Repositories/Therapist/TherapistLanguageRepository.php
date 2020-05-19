<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistLanguage;
use Carbon\Carbon;
use DB;

class TherapistLanguageRepository extends BaseRepository
{
    protected $therapistLanguage;
    public    $isFreelancer = '0', $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapistLanguage = new TherapistLanguage();

        $this->errorMsg   = [];
        $this->successMsg = [];
    }

    public function create(array $data)
    {
        $therapistLanguage = [];
        DB::beginTransaction();

        try {
            $now         = Carbon::now();
            $therapistId = (!empty($data['therapist_id'])) ? (int)$data['therapist_id'] : NULL;
            $languageIds = (!empty($data['language_ids'])) ? $data['language_ids'] : [];

            if (empty($therapistId)) {
                $this->errorMsg[] = "Please provide therapist id.";
            }
            if (empty($languageIds)) {
                $this->errorMsg[] = "Please provide language ids.";
            }

            if ($this->isErrorFree()) {
                foreach ($languageIds as $languageId => $types) {
                    if (!empty($types) && is_array($types)) {
                        foreach ($types as $type => $value) {
                            $getType    = (!empty($this->therapistLanguage->types[strtoupper($type)])) ? $this->therapistLanguage->types[strtoupper($type)] : 0;
                            $data = [
                                'type'         => $getType,
                                'value'        => (string)$value,
                                'language_id'  => $languageId,
                                'therapist_id' => $therapistId
                            ];
                            $validator = $this->therapistLanguage->validator($data);
                            if ($validator->fails()) {
                                $this->errorMsg = $validator->errors()->all();
                            } else {
                                // Check if already exists or not.
                                $isExists = $this->therapistLanguage->where(['therapist_id' => $therapistId, 'language_id' => $languageId, 'type' => $data['type']])->exists();

                                $data['updated_at'] = $now;
                                if ($isExists) {
                                    $updateData[] = $data;
                                } else {
                                    $data['created_at'] = $now;
                                    $insertData[]       = $data;
                                }
                            }
                        }
                    } else {
                        $this->errorMsg[] = "Please provide type properly as R, W, S";
                    }
                }

                if ($this->isErrorFree()) {
                    $therapistLanguage = $this->therapistLanguage;

                    if (!empty($updateData)) {
                        foreach ($updateData as $data) {
                            $therapistLanguage->where(['therapist_id' => $data['therapist_id'], 'language_id' => $data['language_id'], 'type' => $data['type']])->update([
                                'type'       => $data['type'],
                                'value'      => $data['value'],
                                'updated_at' => $data['updated_at']
                            ]);
                        }
                    }
                    if (!empty($insertData)) {
                        $therapistLanguage->insert($insertData);
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        $this->successMsg[] = "Therapist language added successfully !";

        return $this;
    }

    public function all()
    {
        return $this->therapistLanguage->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapist->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistLanguage->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist calender found successfully !',
                'data' => $data
                ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $therapistId, string $date)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapistLanguage = $this->therapistLanguage->find($id);

        if (!empty($therapistLanguage)) {
            return $therapistLanguage->get();
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

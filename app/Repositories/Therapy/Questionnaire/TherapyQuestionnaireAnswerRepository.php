<?php

namespace App\Repositories\Therapy\Questionnaire;

use App\Repositories\BaseRepository;
use App\TherapyQuestionnaireAnswer;
use Carbon\Carbon;
use DB;

class TherapyQuestionnaireAnswerRepository extends BaseRepository
{
    protected $therapyQuestionnaireAnswer;

    public function __construct()
    {
        parent::__construct();
        $this->therapyQuestionnaireAnswer = new TherapyQuestionnaireAnswer();
    }

    public function create(array $data)
    {
        $therapyQuestionnaireAnswer = [];
        DB::beginTransaction();

        try {
            $userId = (!empty($data['user_id'])) ? (int)$data['user_id'] : false;
            $data   = (!empty($data['data'])) ? (array)$data['data'] : [];
            $data   = (!isMultidimentional($data)) ? [$data] : $data;
            $now    = Carbon::now();

            if (!$userId) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Please provide valid user_id.'
                ]);
            }

            if (empty($data)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Data should not be empty and it should be valid.'
                ]);
            }

            $insertData = $matchIds = [];
            foreach ($data as $index => $answers) {
                if (!empty($answers['id'])) {
                    $questionId = (int)$answers['id'];
                    $value      = (!empty($answers['value'])) ? $answers['value'] : NULL;

                    $matchIds[$index] = [
                        'questionnaire_id' => $questionId,
                        'user_id'          => $userId
                    ];

                    $insertData[$index] = [
                        'value'            => $value,
                        'questionnaire_id' => $questionId,
                        'user_id'          => $userId,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];

                    $validator = $this->therapyQuestionnaireAnswer->validator($insertData[$index]);
                    if ($validator->fails()) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => $validator->errors()->first()
                        ]);
                    }

                    $therapyQuestionnaireAnswer = $this->therapyQuestionnaireAnswer->updateOrCreate($matchIds[$index], $insertData[$index]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapy questionnaire answers created successfully !'
        ]);
    }

    public function all()
    {
        return $this->therapyQuestionnaireAnswer->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapyQuestionnaireAnswer->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapyQuestionnaireAnswer->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapy questionnaire answers found successfully !',
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

    public function get(array $data, int $limit = 10)
    {}

    public function errors()
    {}
}

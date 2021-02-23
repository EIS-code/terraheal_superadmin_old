<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistReviewQuestion;
use DB;

class TherapistReviewQuestionRepository extends BaseRepository
{
    protected $therapistReviewQuestion;

    public function __construct()
    {
        parent::__construct();
        $this->therapistReviewQuestion = new TherapistReviewQuestion();
    }

    public function create(array $data)
    {}

    public function all($isApi = false)
    {
        $results = $this->therapistReviewQuestion->all();

        if ($isApi) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist review questions found successfully !',
                'data' => $results
            ]);
        }

        return $results;
    }

    public function getWhere($column, $value)
    {
        return $this->therapistReviewQuestion->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistReviewQuestion->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist review question found successfully !',
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
        $therapistReviewQuestion = $this->therapistReviewQuestion->find($id);

        if (!empty($therapistReviewQuestion)) {
            return $therapistReviewQuestion->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

<?php

namespace App\Repositories\Therapy\Questionnaire;

use App\Repositories\BaseRepository;
use App\TherapyQuestionnaire;
use DB;

class TherapyQuestionnaireRepository extends BaseRepository
{
    protected $therapyQuestionnaire;

    public function __construct()
    {
        parent::__construct();
        $this->therapyQuestionnaire = new TherapyQuestionnaire();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->therapyQuestionnaire->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapyQuestionnaire->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapyQuestionnaire->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapy questionnaire found successfully !',
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
    {
        $query = (!empty($data['q'])) ? $data['q'] : NULL;
        $limit = (!is_numeric($limit)) ? 10 : $limit;

        $getQuestions = $this->therapyQuestionnaire->where(function($sql) use($query) {
            $sql->where("title", "LIKE", "%{$query}%")
                ->orWhere("placeholder", "LIKE", "%{$query}%");
        })->limit($limit)->get();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapy questionnaire found successfully !',
            'data' => $getQuestions
        ]);
    }

    public function errors()
    {}
}

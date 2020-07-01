<?php

namespace App\Http\Controllers\Therapy\Questionnaire;

use App\Http\Controllers\BaseController;

class TherapyQuestionnaireAnswerController extends BaseController
{
    protected $getRequest, $therapyQuestionnaireAnswer;

    public function __construct()
    {
        parent::__construct();
        $this->therapyQuestionnaireAnswer = $this->therapyQuestionnaireAnswerRepo;
        $this->getRequest                 = $this->httpRequest->all();
    }

    public function create()
    {
        return $this->therapyQuestionnaireAnswer->create($this->getRequest);
    }
}

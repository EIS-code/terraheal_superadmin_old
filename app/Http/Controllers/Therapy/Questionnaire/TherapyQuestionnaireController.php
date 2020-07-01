<?php

namespace App\Http\Controllers\Therapy\Questionnaire;

use App\Http\Controllers\BaseController;

class TherapyQuestionnaireController extends BaseController
{
    protected $getRequest, $therapyQuestionnaire;

    public function __construct()
    {
        parent::__construct();
        $this->therapyQuestionnaire = $this->therapyQuestionnaireRepo;
        $this->getRequest           = $this->httpRequest->all();
    }

    public function get()
    {
        return $this->therapyQuestionnaire->get($this->getRequest);
    }
}

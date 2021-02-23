<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\BaseController;

class TherapistReviewController extends BaseController
{
    protected $therapistReview, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest              = $this->httpRequest->all();
        $this->therapistReview         = $this->therapistReviewRepo;
        $this->therapistReviewQuestion = $this->therapistReviewQuestionRepo;
    }

    public function create()
    {
        return $this->therapistReview->create($this->getRequest);
    }

    public function getReviews()
    {
        return $this->therapistReviewQuestion->all(true);
    }
}

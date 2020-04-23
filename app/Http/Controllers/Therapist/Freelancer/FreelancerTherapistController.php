<?php

namespace App\Http\Controllers\Therapist\Freelancer;

use App\Http\Controllers\BaseController;

class FreelancerTherapistController extends BaseController
{
    protected $freelancerTherapist;

    public function __construct()
    {
        parent::__construct();
        $this->freelancerTherapist = $this->freelancerTherapistRepo;
    }

    public function signup()
    {
        $getRequest = $this->httpRequest->all();

        return $this->freelancerTherapist->create($getRequest);
    }

    public function getPastBooking($therapistId)
    {
        return $this->freelancerTherapist->getWherePastFuture($therapistId, true, true);
    }

    public function getFutureBooking($therapistId)
    {
        return $this->freelancerTherapist->getWherePastFuture($therapistId, false, true);
    }
}

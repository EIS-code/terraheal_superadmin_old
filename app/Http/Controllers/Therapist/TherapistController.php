<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\BaseController;

class TherapistController extends BaseController
{
    protected $therapist, $therapistCalendar, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest        = $this->httpRequest->all();
        $this->therapist         = $this->therapistRepo;
        $this->therapistCalendar = $this->therapistCalendarRepo;

        $getPrifix = $this->httpRequest->route()->getPrefix();
        if (strpos($getPrifix, 'freelancer') !== false) {
            $this->therapist->isFreelancer = '1';
        }
    }

    public function signup()
    {
        return $this->therapist->create($this->getRequest);
    }

    public function signIn()
    {
        return $this->therapist->signIn($this->getRequest);
    }

    public function update($therapistId)
    {
        return $this->therapist->update($therapistId, $this->getRequest);
    }

    public function getPastBooking($therapistId)
    {
        return $this->therapist->getWherePastFuture($therapistId, true, true);
    }

    public function getFutureBooking($therapistId)
    {
        return $this->therapist->getWherePastFuture($therapistId, false, true);
    }

    public function search()
    {
        return $this->therapist->search($this->getRequest);
    }

    public function verifyMobile($therapistId)
    {
        return $this->response($this->therapist->verifyMobile($therapistId, $this->getRequest));
    }

    public function compareOtpSms($therapistId)
    {
        return $this->response($this->therapist->compareOtpSms($therapistId, $this->getRequest));
    }

    public function verifyEmail($therapistId)
    {
        return $this->response($this->therapist->verifyEmail($therapistId, $this->getRequest));
    }

    public function compareOtpEmail($therapistId)
    {
        return $this->response($this->therapist->compareOtpEmail($therapistId, $this->getRequest));
    }

    public function updateProfile($therapistId)
    {
        return $this->therapist->updateProfile($therapistId, $this->httpRequest);
    }

    public function getDetails($therapistId)
    {
        return $this->therapist->getGlobalResponse($therapistId, true);
    }
}

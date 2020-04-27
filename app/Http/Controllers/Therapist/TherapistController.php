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
    }

    public function signup()
    {
        return $this->therapist->create($this->getRequest);
    }

    public function getPastBooking($therapistId)
    {
        return $this->therapist->getWherePastFuture($therapistId, true, true);
    }

    public function getFutureBooking($therapistId)
    {
        return $this->therapist->getWherePastFuture($therapistId, false, true);
    }

    public function createCalendar()
    {
        return $this->therapistCalendar->create($this->getRequest);
    }

    public function updateTimeCalendar($therapistId, $date)
    {
        return $this->therapistCalendar->updateTime($therapistId, $date, $this->getRequest);
    }

    public function deleteCalendar($therapistId, $date)
    {
        return $this->therapistCalendar->delete($therapistId, $date);
    }
}

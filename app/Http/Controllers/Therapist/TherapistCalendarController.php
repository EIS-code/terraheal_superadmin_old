<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\BaseController;

class TherapistCalendarController extends BaseController
{
    protected $therapistCalendar, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest        = $this->httpRequest->all();
        $this->therapistCalendar = $this->therapistCalendarRepo;

        $getPrifix = $this->httpRequest->route()->getPrefix();
        if (strpos($getPrifix, 'freelancer') !== false) {
            $this->therapistCalendar->isFreelancer = '1';
        }
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

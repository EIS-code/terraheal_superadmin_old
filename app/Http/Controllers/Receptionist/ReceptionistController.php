<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\BaseController;

class ReceptionistController extends BaseController
{
    protected $getRequest, $receptionist;

    public function __construct()
    {
        parent::__construct();
        $this->receptionist = $this->receptionistRepo;

        $this->getRequest = $this->httpRequest->all();
    }

    public function signup()
    {
        return $this->receptionist->create($this->getRequest);
    }

    public function getPastBooking($shopId)
    {
        return $this->receptionist->getWherePastFutureToday($shopId, 'past', false, true);
    }

    public function getFutureBooking($shopId)
    {
        return $this->receptionist->getWherePastFutureToday($shopId, 'future', false, true);
    }

    public function getTodayBooking($shopId)
    {
        return $this->receptionist->getWherePastFutureToday($shopId, 'today', false, true);
    }

    public function getDoneBooking($shopId)
    {
        return $this->receptionist->getWherePastFutureToday($shopId, 'today', true, true);
    }
}

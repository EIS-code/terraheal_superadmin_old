<?php

namespace App\Http\Controllers\Therapist\Massage;

use App\Http\Controllers\BaseController;

class TherapistMassageHistoryController extends BaseController
{
    protected $getRequest, $therapistMassageHistory;

    public function __construct()
    {
        parent::__construct();
        $this->therapistMassageHistory = $this->therapistMassageHistoryRepo;

        $this->getRequest = $this->httpRequest->all();
    }

    public function startMassage($bookingInfoId)
    {
        return $this->therapistMassageHistory->startMassage($bookingInfoId);
    }

    public function completeMassage($bookingInfoId)
    {
        return $this->therapistMassageHistory->completeMassage($bookingInfoId);
    }

    public function pauseMassage($bookingInfoId)
    {
        return $this->therapistMassageHistory->pauseMassage($bookingInfoId);
    }

    public function restartMassage($bookingInfoId)
    {
        return $this->therapistMassageHistory->restartMassage($bookingInfoId);
    }
}

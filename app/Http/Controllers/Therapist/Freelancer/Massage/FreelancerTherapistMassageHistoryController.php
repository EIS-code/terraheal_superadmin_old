<?php

namespace App\Http\Controllers\Therapist\Freelancer\Massage;

use App\Http\Controllers\BaseController;

class FreelancerTherapistMassageHistoryController extends BaseController
{
    protected $getRequest, $freelancerTherapistMassageHistory;

    public function __construct()
    {
        parent::__construct();
        $this->freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistoryRepo;

        $this->getRequest = $this->httpRequest->all();
    }

    public function startMassage($bookingInfoId)
    {
        return $this->freelancerTherapistMassageHistory->startMassage($bookingInfoId);
    }

    public function completeMassage($bookingInfoId)
    {
        return $this->freelancerTherapistMassageHistory->completeMassage($bookingInfoId);
    }

    public function pauseMassage($bookingInfoId)
    {
        return $this->freelancerTherapistMassageHistory->pauseMassage($bookingInfoId);
    }

    public function restartMassage($bookingInfoId)
    {
        return $this->freelancerTherapistMassageHistory->restartMassage($bookingInfoId);
    }
}

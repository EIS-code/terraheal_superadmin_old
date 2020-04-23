<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\BaseController;

class ReceptionistController extends BaseController
{
    //
    protected $receptionist;

    public function __construct()
    {
        parent::__construct();
        $this->receptionist = $this->receptionistRepo;
    }

    public function getPastBooking($shopId)
    {
        return $this->receptionist->getWherePastFuture($shopId, true, true);
    }

    public function getFutureBooking($shopId)
    {
        return $this->receptionist->getWherePastFuture($shopId, false, true);
    }
}

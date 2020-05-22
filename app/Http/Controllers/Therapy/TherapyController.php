<?php

namespace App\Http\Controllers\Therapy;

use App\Http\Controllers\BaseController;

class TherapyController extends BaseController
{
    protected $getRequest, $therapy;

    public function __construct()
    {
        parent::__construct();
        $this->therapy    = $this->therapyRepo;
        $this->getRequest = $this->httpRequest->all();
    }

    public function get()
    {
        return $this->therapy->get($this->getRequest);
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\BaseController;

class StaffController extends BaseController
{
    protected $staff, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest = $this->httpRequest->all();
        $this->staff      = $this->staffRepo;
    }

    public function signup()
    {
        return $this->staff->create($this->getRequest);
    }

    public function update($staffId)
    {
        return $this->staff->update($staffId, $this->getRequest);
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\BaseController;

class StaffAttendanceController extends BaseController
{
    protected $staffAttendance, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest      = $this->httpRequest->all();
        $this->staffAttendance = $this->staffAttendanceRepo;
    }

    public function attendanceIn($staffId)
    {
        return $this->staffAttendance->attendanceIn($staffId, $this->getRequest);
    }

    public function attendanceBreakIn($staffId)
    {
        return $this->staffAttendance->attendanceBreakIn($staffId, $this->getRequest);
    }

    public function attendanceBreakOut($staffId)
    {
        return $this->staffAttendance->attendanceBreakOut($staffId, $this->getRequest);
    }

    public function attendanceOut($staffId)
    {
        return $this->staffAttendance->attendanceOut($staffId, $this->getRequest);
    }
}

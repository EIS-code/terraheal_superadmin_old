<?php

namespace App\Http\Controllers\Massage\Preference;

use App\Http\Controllers\BaseController;

class MassagePreferenceController extends BaseController
{
    protected $getRequest, $massagePreference;

    public function __construct()
    {
        parent::__construct();
        $this->massagePreference = $this->massagePreferenceRepo;
        $this->getRequest 		 = $this->httpRequest->all();
    }

    public function get()
    {
        return $this->massagePreference->get($this->getRequest);
    }
}

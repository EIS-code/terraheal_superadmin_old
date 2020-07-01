<?php

namespace App\Http\Controllers\Massage\Preference;

use App\Http\Controllers\BaseController;

class SelectedMassagePreferenceController extends BaseController
{
    protected $getRequest, $selectedMassagePreferenceRepo;

    public function __construct()
    {
        parent::__construct();
        $this->selectedMassagePreference = $this->selectedMassagePreferenceRepo;
        $this->getRequest                = $this->httpRequest->all();
    }

    public function create()
    {
        return $this->selectedMassagePreference->create($this->getRequest);
    }
}

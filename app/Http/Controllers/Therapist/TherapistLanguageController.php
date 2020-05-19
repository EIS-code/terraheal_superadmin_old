<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\BaseController;

class TherapistLanguageController extends BaseController
{
    protected $therapistLanguage, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest        = $this->httpRequest->all();
        $this->therapistLanguage = $this->therapistLanguageRepo;

        $getPrifix = $this->httpRequest->route()->getPrefix();
        if (strpos($getPrifix, 'freelancer') !== false) {
            $this->therapistLanguage->isFreelancer = '1';
        }
    }

    public function addLanguage()
    {
        return $this->response($this->therapistLanguage->create($this->getRequest));
    }
}

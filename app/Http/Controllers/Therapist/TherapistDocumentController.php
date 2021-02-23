<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\BaseController;

class TherapistDocumentController extends BaseController
{
    protected $therapistDocument, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->getRequest        = $this->httpRequest->all();
        $this->therapistDocument = $this->therapistDocumentRepo;

        $getPrifix = $this->httpRequest->route()->getPrefix();
        if (strpos($getPrifix, 'freelancer') !== false) {
            $this->therapistDocument->isFreelancer = '1';
        }
    }

    public function create()
    {
        $therapistId = $this->httpRequest->get('therapist_id', false);
        // return $this->response($this->therapistDocument->create($therapistId, $this->httpRequest));
        return $this->therapistDocument->create($therapistId, $this->httpRequest);
    }
}

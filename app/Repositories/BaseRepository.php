<?php

namespace App\Repositories;

use App\Repositories\EmailRepository;
use App\Repositories\SmsRepository;

class BaseRepository
{
    public $emailRepo, $smsRepo;

    public function __construct()
    {
        $this->emailRepo = new EmailRepository();
        $this->smsRepo   = new SmsRepository();
    }

    public function getJsonResponseCode($response)
    {
        if (!empty($response) && $response instanceof \Illuminate\Http\JsonResponse) {
            return (!empty($response->getData()->code)) ? $response->getData()->code : false;
        }

        return false;
    }
}

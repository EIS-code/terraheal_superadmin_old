<?php

namespace App\Repositories;

use App\Repositories\EmailRepository;
use App\Repositories\SmsRepository;

class BaseRepository
{
    public $emailRepo, $smsRepo;

    const PAGINATE_RECORDS = 20;

    public function __construct()
    {
        $this->emailRepo   = new EmailRepository();
        $this->smsRepo     = new SmsRepository();
    }

    public function getJsonResponseCode($response)
    {
        if (!empty($response) && $response instanceof \Illuminate\Http\JsonResponse) {
            return (!empty($response->getData()->code)) ? $response->getData()->code : false;
        }

        return false;
    }

    public function getJsonResponseMsg($response)
    {
        if (!empty($response) && $response instanceof \Illuminate\Http\JsonResponse) {
            return (!empty($response->getData()->msg)) ? $response->getData()->msg : false;
        }

        return false;
    }

    public function getJsonResponseOtp($response)
    {
        if (!empty($response) && $response instanceof \Illuminate\Http\JsonResponse) {
            return (!empty($response->getData()->otp)) ? $response->getData()->otp : false;
        }

        return false;
    }
}

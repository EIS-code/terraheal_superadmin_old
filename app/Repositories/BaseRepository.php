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
}

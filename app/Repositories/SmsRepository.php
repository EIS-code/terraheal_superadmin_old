<?php

namespace App\Repositories;

use App\Sms;
use Carbon\Carbon;

class SmsRepository
{
    protected $sms;

    public function __construct()
    {
        $this->sms = new Sms();
    }

    public function send(string $number, string $body)
    {
        /* TODO : For send SMS. */
        $validator = $this->sms->validator(['number' => $number, 'body' => $body]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'msg'  => $validator->errors()->first()
            ]);
        }

        $this->insertSms([
            'number'     => $number,
            'body'       => $body,
            'created_at' => Carbon::now()
        ]);

        return response()->json([
            'code' => 200,
            'msg'  => 'SMS sent successfully !'
        ]);
    }

    public function insertSms(array $data)
    {
        return $this->sms->insert($data);
    }

    public function sendOtp(string $number)
    {
        $otp  = mt_rand(1000,9999);
        $body = $otp . ' is your OTP for verification on the Terra Heal.';

        $return = $this->send($number, $body);

        if ($return->getData()->code == 200) {
            return response()->json([
                'code' => 200,
                'msg'  => 'SMS sent successfully !',
                'otp'  => $otp
            ]);
        } else {
            return $return;
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->userRepo;
    }

    public function signupFacebook()
    {
        return view('test/signup-facebook');
    }

    public function signupGoogle()
    {
        return view('test/signup-google');
    }

    public function signup()
    {
        $getRequest = $this->httpRequest->all();

        return $this->user->create($getRequest);
    }

    public function sendOtpEmail($email)
    {
        return $this->user->emailRepo->sendOtp($email);
    }

    public function sendOtpSms($number)
    {
        return $this->user->smsRepo->sendOtp($number);
    }

    public function update($id)
    {
        $getRequest = $this->httpRequest->all();

        return $this->user->update($id, $getRequest);
    }
}

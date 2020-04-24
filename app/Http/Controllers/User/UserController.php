<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    protected $user, $booking, $review;

    public function __construct()
    {
        parent::__construct();
        $this->user    = $this->userRepo;
        $this->booking = $this->bookingRepo;
        $this->review  = $this->reviewRepo;
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

    public function getDetails($id)
    {
        return $this->user->getWhereFirst('id', $id, true);
    }

    public function bookingCreate()
    {
        $getRequest = $this->httpRequest->all();

        return $this->booking->create($getRequest);
    }

    public function bookingUpdate($bookingInfoId)
    {
        $getRequest = $this->httpRequest->all();

        return $this->booking->update($bookingInfoId, $getRequest);
    }

    public function getPastBooking($userId)
    {
        return $this->booking->getWherePastFuture($userId, true, true);
    }

    public function getFutureBooking($userId)
    {
        return $this->booking->getWherePastFuture($userId, false, true);
    }

    public function reviewCreate($userId, $rating)
    {
        return $this->review->create($userId, $rating);
    }

    public function reviewDelete($reviewId)
    {
        return $this->review->delete($reviewId, true);
    }

    public function addRoom($bookingInfoId, $roomId)
    {
        return $this->booking->addRoom($bookingInfoId, $roomId);
    }
}

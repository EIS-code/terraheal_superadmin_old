<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    protected $user, $booking, $review, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->user       = $this->userRepo;
        $this->booking    = $this->bookingRepo;
        $this->review     = $this->reviewRepo;
        $this->getRequest = $this->httpRequest->all();
    }

    public function signupFacebook()
    {
        return view('test/signup-facebook');
    }

    public function signupGoogle()
    {
        return view('test/signup-google');
    }

    public function signUp()
    {
        return $this->user->create($this->getRequest);
    }

    public function signIn()
    {
        return $this->user->signIn($this->getRequest);
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
        return $this->user->update($id, $this->getRequest);
    }

    /* public function getDetails($id)
    {
        return $this->user->getWhereFirst('id', $id, true);
    } */

    public function bookingCreate()
    {
        return $this->booking->create($this->getRequest);
    }

    public function bookingUpdate($bookingInfoId)
    {
        return $this->booking->update($bookingInfoId, $this->getRequest);
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

    public function updateProfile()
    {
        $userId = $this->httpRequest->get('user_id', false);
        return $this->user->updateProfile($userId, $this->httpRequest);
    }

    public function verifyEmail()
    {
        return $this->response($this->user->verifyEmail($this->getRequest));
    }

    public function verifyMobile()
    {
        return $this->response($this->user->verifyMobile($this->getRequest));
    }

    public function compareOtpEmail()
    {
        return $this->response($this->user->compareOtpEmail($this->getRequest));
    }

    public function compareOtpSms()
    {
        return $this->response($this->user->compareOtpSms($this->getRequest));
    }

    public function getDetails($userId)
    {
        return $this->user->getGlobalResponse($userId, true);
    }
}

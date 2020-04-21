<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Repositories\User\BookingRepository;
use App\Repositories\User\ReviewRepository;
use App\Repositories\Location\CountryRepository;
use App\Repositories\Location\CityRepository;
use App\Repositories\User\Payment\BookingPaymentRepository;
use App\Repositories\User\Payment\UserCardDetailRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;
    protected $userRepo, $bookingRepo, $reviewRepo, $countryRepo, $cityRepo, $bookingPaymentRepo, $userCardDetailRepo;

    public function __construct()
    {
        $this->httpRequest        = Request();
        $this->userRepo           = new UserRepository();
        $this->bookingRepo        = new BookingRepository();
        $this->reviewRepo         = new ReviewRepository();
        $this->countryRepo        = new CountryRepository();
        $this->cityRepo           = new CityRepository();
        $this->bookingPaymentRepo = new BookingPaymentRepository();
        $this->userCardDetailRepo = new UserCardDetailRepository();
    }
}

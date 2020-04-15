<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Repositories\User\BookingRepository;
use App\Repositories\User\ReviewRepository;
use App\Repositories\Location\CountryRepository;
use App\Repositories\Location\CityRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;
    protected $userRepo, $bookingRepo, $reviewRepo, $countryRepo, $cityRepo;

    public function __construct()
    {
        $this->httpRequest = Request();
        $this->userRepo    = new UserRepository();
        $this->bookingRepo = new BookingRepository();
        $this->reviewRepo  = new ReviewRepository();
        $this->countryRepo = new CountryRepository();
        $this->cityRepo    = new CityRepository();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Repositories\User\BookingRepository;
use App\Repositories\User\ReviewRepository;
use App\Repositories\Location\CountryRepository;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\CityRepository;
use App\Repositories\User\Payment\BookingPaymentRepository;
use App\Repositories\User\Payment\UserCardDetailRepository;
use App\Repositories\Therapist\TherapistRepository;
use App\Repositories\Therapist\Massage\TherapistMassageHistoryRepository;
use App\Repositories\Therapist\TherapistCalendarRepository;
use App\Repositories\Receptionist\ReceptionistRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;
    protected $userRepo, $bookingRepo, $reviewRepo, $countryRepo, $provinceRepo, $cityRepo, $bookingPaymentRepo, $userCardDetailRepo, $therapist, $therapistMassageHistoryRepo, $receptionistRepo, $therapistCalendarRepo;

    public function __construct()
    {
        $this->httpRequest             = Request();
        $this->userRepo                = new UserRepository();
        $this->bookingRepo             = new BookingRepository();
        $this->reviewRepo              = new ReviewRepository();
        $this->countryRepo             = new CountryRepository();
        $this->provinceRepo            = new ProvinceRepository();
        $this->cityRepo                = new CityRepository();
        $this->bookingPaymentRepo      = new BookingPaymentRepository();
        $this->userCardDetailRepo      = new UserCardDetailRepository();
        $this->therapistRepo           = new therapistRepository();
        $this->therapistMassageHistoryRepo = new therapistMassageHistoryRepository();
        $this->receptionistRepo        = new ReceptionistRepository();
        $this->therapistCalendarRepo   = new TherapistCalendarRepository();
    }
}

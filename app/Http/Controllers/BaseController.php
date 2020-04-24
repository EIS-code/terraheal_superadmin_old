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
use App\Repositories\Therapist\Freelancer\FreelancerTherapistRepository;
use App\Repositories\Therapist\Freelancer\Massage\FreelancerTherapistMassageHistoryRepository;
use App\Repositories\Receptionist\ReceptionistRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;
    protected $userRepo, $bookingRepo, $reviewRepo, $countryRepo, $provinceRepo, $cityRepo, $bookingPaymentRepo, $userCardDetailRepo, $freelancerTherapist, $freelancerTherapistMassageHistoryRepo, $receptionistRepo;

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
        $this->freelancerTherapistRepo = new FreelancerTherapistRepository();
        $this->freelancerTherapistMassageHistoryRepo = new FreelancerTherapistMassageHistoryRepository();
        $this->receptionistRepo        = new ReceptionistRepository();
    }
}

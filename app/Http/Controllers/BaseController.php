<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;
use App\Repositories\User\UserRepository;
use App\Repositories\User\BookingRepository;
use App\Repositories\User\ReviewRepository;
use App\Repositories\Location\CountryRepository;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\CityRepository;
use App\Repositories\User\Payment\BookingPaymentRepository;
use App\Repositories\User\Payment\UserCardDetailRepository;
use App\Repositories\User\Address\UserAddressRepository;
use App\Repositories\User\People\UserPeopleRepository;
use App\Repositories\User\Setting\UserSettingRepository;
use App\Repositories\Therapist\TherapistRepository;
use App\Repositories\Therapist\Massage\TherapistMassageHistoryRepository;
use App\Repositories\Therapist\TherapistCalendarRepository;
use App\Repositories\Therapist\TherapistLanguageRepository;
use App\Repositories\Therapist\TherapistReviewQuestionRepository;
use App\Repositories\Therapist\TherapistReviewRepository;
use App\Repositories\Therapist\TherapistDocumentRepository;
use App\Repositories\Receptionist\ReceptionistRepository;
use App\Repositories\Staff\StaffRepository;
use App\Repositories\Staff\StaffAttendanceRepository;
use App\Repositories\Massage\MassageRepository;
use App\Repositories\Therapy\TherapyRepository;
use App\Repositories\Therapy\Questionnaire\TherapyQuestionnaireRepository;
use App\Repositories\Therapy\Questionnaire\TherapyQuestionnaireAnswerRepository;
use App\Repositories\Massage\Preference\MassagePreferenceRepository;
use App\Repositories\Massage\Preference\SelectedMassagePreferenceRepository;
use App\Repositories\User\FocusAreaRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null, $defaultCode = 0, $defaultMessage = "No any response found !", $errorCode = 401, $successCode = 200;
    protected $userRepo, $bookingRepo, $reviewRepo, $countryRepo, $provinceRepo, $cityRepo, $bookingPaymentRepo, $userCardDetailRepo,
              $therapist, $therapistMassageHistoryRepo, $receptionistRepo, $therapistCalendarRepo, $therapistLanguageRepo,
              $therapistReviewQuestionRepo, $therapistReviewRepo, $staffRepo, $staffAttendanceRepo, $massageRepo, $therapistDocumentRepo,
              $therapyRepo, $massagePreferenceRepo, $selectedMassagePreferenceRepo, $therapyQuestionnaireRepo, $therapyQuestionnaireAnswerRepo,
              $userAddressRepo, $userPeopleRepo, $userSettingRepo, $focusAreaRepo;

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
        $this->userAddressRepo         = new UserAddressRepository();
        $this->userPeopleRepo          = new UserPeopleRepository();
        $this->userSettingRepo         = new UserSettingRepository();
        $this->therapistRepo           = new therapistRepository();
        $this->therapistMassageHistoryRepo = new therapistMassageHistoryRepository();
        $this->receptionistRepo        = new ReceptionistRepository();
        $this->therapistReviewQuestionRepo = new TherapistReviewQuestionRepository();
        $this->therapistReviewRepo     = new TherapistReviewRepository();
        $this->therapistCalendarRepo   = new TherapistCalendarRepository();
        $this->therapistLanguageRepo   = new TherapistLanguageRepository();
        $this->therapistDocumentRepo   = new TherapistDocumentRepository();
        $this->staffRepo               = new StaffRepository();
        $this->staffAttendanceRepo     = new StaffAttendanceRepository();
        $this->massageRepo             = new MassageRepository();
        $this->therapyRepo             = new TherapyRepository();
        $this->therapyQuestionnaireRepo = new TherapyQuestionnaireRepository();
        $this->therapyQuestionnaireAnswerRepo = new TherapyQuestionnaireAnswerRepository();
        $this->massagePreferenceRepo   = new MassagePreferenceRepository();
        $this->selectedMassagePreferenceRepo = new SelectedMassagePreferenceRepository();
        $this->focusAreaRepo           = new FocusAreaRepository();
    }

    public function response($response = [])
    {
        $responseCode    = $this->defaultCode;
        $responseMessage = $this->defaultMessage;

        if (!empty($response->errorMsg)) {
            $responseCode    = $this->errorCode;
            $responseMessage = (is_array($response->errorMsg)) ? reset($response->errorMsg) : $response->errorMsg;
        } elseif (!empty($response->successMsg)) {
            $responseCode    = $this->successCode;
            $responseMessage = $response->successMsg;
        }

        return response()->json([
            'code' => $responseCode,
            'msg'  => $responseMessage
        ]);
    }

    public function getStorageFiles($path, $isGetFile = true)
    {
        // $path = storage_path('public/' . $filename);

        if (!File::exists($path)) {
            // abort(404);
            return false;
        }

        $file = File::get($path);

        if ($isGetFile) {
            return $file;
        }

        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}

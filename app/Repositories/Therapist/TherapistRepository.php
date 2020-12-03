<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\TherapistDocumentRepository;
use App\Repositories\Therapist\TherapistReviewRepository;
use App\Repositories\Therapist\TherapistEmailOtpRepository;
use App\Repositories\Therapist\TherapistSelectedMassageRepository;
use App\Repositories\Therapist\TherapistSelectedTherapyRepository;
use App\Therapist;
use App\TherapistReview;
use App\Booking;
use App\BookingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use CurrencyHelper;
use DB;

class TherapistRepository extends BaseRepository
{
    protected $therapist, $therapistDocumentRepo, $therapistReviewRepository, $booking, $bookingInfo, $currencyHelper, $profilePhotoPath, $therapistEmailOtpRepo, $therapistSelectedMassageRepo, $therapistSelectedTherapyRepo;
    public    $isFreelancer = '0', $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapist                    = new Therapist();
        $this->therapistDocumentRepo        = new therapistDocumentRepository();
        $this->therapistReviewRepository    = new TherapistReviewRepository();
        $this->therapistEmailOtpRepo        = new TherapistEmailOtpRepository();
        $this->therapistSelectedMassageRepo = new TherapistSelectedMassageRepository();
        $this->therapistSelectedTherapyRepo = new TherapistSelectedTherapyRepository();
        $this->therapistReview              = new TherapistReview();
        $this->booking                      = new Booking();
        $this->bookingInfo                  = new BookingInfo();
        $this->currencyHelper               = new CurrencyHelper();

        $this->profilePhotoPath             = $this->therapist->profilePhotoPath;
    }

    public function create(array $data)
    {
        $therapist = [];
        DB::beginTransaction();

        try {
            $data['is_freelancer'] = $this->isFreelancer;
            $validator = $this->therapist->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            // Clone avatar / origional image for profile photos.
            if (!empty($data['avatar']) || !empty($data['avatar_original'])) {
                $image       = (!empty($data['avatar'])) ? $data['avatar'] : $data['avatar_original'];
                $getPathInfo = pathinfo($image);
                if (!empty($getPathInfo['basename'])) {
                    if (!is_dir(storage_path('app\\' . $this->profilePhotoPath))) {
                        mkdir(storage_path('app\\' . $this->profilePhotoPath));
                    }
                    $isUpload = copy($image, storage_path('app\\' . $this->profilePhotoPath) . $getPathInfo['basename']);

                    if ($isUpload) {
                        $data['profile_photo'] = $getPathInfo['basename'];
                    }
                }
            }

            $therapist        = $this->therapist;
            $data['password'] = (!empty($data['password']) ? Hash::make($data['password']) : NULL);
            $therapist->fill($data);
            if ($therapist->save() && !empty($data['documents'])) {
                $this->therapistDocumentRepo->create($data['documents'], $therapist->id);
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist created successfully !',
            'data' => $this->getGlobalResponse($therapist->id)
        ]);
    }

    public function setReviews(array $data)
    {
        if (!empty($data)) {
            $validator = $this->therapistReview->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $therapistReview = $this->therapistReview;

            $data['rating'] = (float)$data['rating'];

            $therapistReview->fill($data);
            if ($therapistReview->save()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Therapist review created successfully !'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Something went wrong.'
        ]);
    }

    public function all()
    {
        return $this->therapist->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapist->where($column, $value)->get();
    }

    public function getWhereMany(array $where)
    {
        return $this->therapist->where($where)->get();
    }

    public function filter(array $data)
    {
        $isFiltered  = false;

        if (count($data) > 0) {
            $isFiltered = (!empty(array_filter($data)));
        }

        $therapists = $this->therapist::query();

        if ($isFiltered) {}

        $therapists = $therapists->paginate($this::PAGINATE_RECORDS);

        return $therapists;
    }

    public function getWherePastFuture($request, $isPast = true, $isApi = false, $isToday = false)
    {
        $now = Carbon::now();

        if (empty($request['therapist_id'])) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Therapist ID not found !'
            ]);
        }

        $therapistId = (int)$request['therapist_id'];

        $massageDate = false;
        if (!empty($request['massage_date'])) {
            $massageDate = strlen($request['massage_date']) > 10 ? ($request['massage_date'] / 1000) : $request['massage_date'];
            $massageDate = Carbon::createFromTimestamp($massageDate)->toDate();
        }

        $clientName = NULL;
        if (!empty($request['client_name'])) {
            $clientName = $request['client_name'];
        }

        if ($isToday) {
            $bookings = $this->booking
                             ->with(['bookingInfo' => function($query) use($therapistId, $now, $massageDate, $clientName) {
                                    $query->whereDate('massage_date', '=', $now)
                                          ->where('therapist_id', $therapistId);

                                    if (!empty($massageDate)) {
                                        $query->whereDate('massage_date', $massageDate);
                                    }

                                    if (!empty($clientName)) {
                                        $query->with(['userPeople' => function($user) use($clientName) {
                                            $user->where('name', 'LIKE', "%{$clientName}%");
                                        }]);
                                    }
                             }]);
        } else {
            $bookings = $this->booking
                             ->with(['bookingInfo' => function($query) use($therapistId, $now, $isPast, $massageDate, $clientName) {
                                    $query->whereDate('massage_date', ($isPast === true ? '<' : '>='), $now)
                                          ->where('therapist_id', $therapistId);

                                    if (!empty($massageDate)) {
                                        $query->whereDate('massage_date', $massageDate);
                                    }

                                    if (!empty($clientName)) {
                                        $query->with(['userPeople' => function($user) use($clientName) {
                                            $user->where('name', 'LIKE', "%{$clientName}%");
                                        }]);
                                    }
                             }]);
        }

        if (isset($request['booking_type']) && ($request['booking_type'] == 0 || $request['booking_type'] == 1)) {
            $bookingType = $request['booking_type'];

            $bookings->where('booking_type', $bookingType);
        }

        if (!empty($request['session_id'])) {
            $sessionId = $request['session_id'];

            $bookings->where('session_id', $sessionId);
        }

        $bookings = $bookings->get();

        if ($isApi === true) {
            $messagePrefix = $isToday ? 'Today' : (($isPast) ? 'Past' : 'Future');
            if (!empty($bookings)) {
                $bookings->map(function($data, $index) use($bookings, $clientName) {
                    if (empty($data->bookingInfo) || $data->bookingInfo->isEmpty()) {
                        unset($bookings[$index]);
                    } elseif (!empty($clientName) && empty($data->bookingInfo[0]->userPeople)) {
                        unset($bookings[$index]);
                    }
                });

                $message = $messagePrefix . ' booking found successfully !';
            } else {
                $message = $messagePrefix . ' booking not found !';
            }
            return response()->json([
                'code' => 200,
                'msg'  => $message,
                'data' => $bookings
            ]);
        }

        return $bookings;
    }

    public function getWhereFirst($column, $value, bool $isApi = false)
    {
        $data = $this->therapist->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function getGlobalResponse(int $id, bool $isApi = false)
    {
        $data = $this->therapist->with('selectedMassages', 'selectedTherapies')->where('id', $id)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {
        $update        = false;
        $findTherapist = $this->therapist->where(['id' => $id, 'is_freelancer' => $this->isFreelancer])->get();

        if (!empty($findTherapist) && !$findTherapist->isEmpty()) {
            DB::beginTransaction();

            try {
                if (isset($data['password'])) {
                    unset($data['password']);
                }
                $data['is_freelancer'] = $this->isFreelancer;
                $validator = $this->therapist->validator($data, $id, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->therapist->where(['id' => $id, 'is_freelancer' => $this->isFreelancer])->update($data);
            } catch (Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Therapist updated successfully !'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Something went wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Therapist not found.'
        ]);
    }

    public function updateProfile(int $therapistId, Request $request)
    {
        $therapist = [];
        DB::beginTransaction();

        try {
            $data = $request->all();
            $now  = Carbon::now();
            if (isset($data['password'])) {
                unset($data['password']);
            }

            if (empty($therapistId)) {
                $this->errorMsg[] = "Please provide valid therapist id.";
                return $this;
            }

            $getTherapist = $this->getWhereFirst('id', $therapistId);
            if (empty($getTherapist)) {
                $this->errorMsg[] = "Please provide valid therapist id.";
                return $this;
            }

            if (!empty($request->profile_photo)) {
                $validate = $this->therapist->validateProfilePhoto($request);
                if ($validate->fails()) {
                    $this->errorMsg = $validate->errors();
                }
            }

            $validateDataMassages = [];
            if (!empty($data['selected_massages'])) {
                $selectedMassages = (is_array($data['selected_massages'])) ? $data['selected_massages'] : [$data['selected_massages']];
                foreach ($selectedMassages as $index => $selectedMassage) {
                    // Check already exists or not.
                    $exists = $this->therapistSelectedMassageRepo->getWhereMany(['therapist_id' => $therapistId, 'massage_id' => $selectedMassage]);
                    if (empty($exists) || $exists->isEmpty()) {
                        $validateDataMassages[$index] = [
                            'therapist_id' => $therapistId,
                            'massage_id'   => $selectedMassage
                        ];
                        $validateDataMassages[$index]['created_at'] = $now;
                        $validateDataMassages[$index]['updated_at'] = $now;
                        $validate = $this->therapistSelectedMassageRepo->validate($validateDataMassages[$index]);
                        if ($validate['is_validate'] == '0') {
                            $this->errorMsg[] = $validate['msg'];
                            break;
                        }
                    }
                }
            }

            $validateDataTherapies = [];
            if (!empty($data['selected_therapies'])) {
                $selectedTherapies = (is_array($data['selected_therapies'])) ? $data['selected_therapies'] : [$data['selected_therapies']];
                foreach ($selectedTherapies as $index => $selectedTherapy) {
                    // Check already exists or not.
                    $exists = $this->therapistSelectedTherapyRepo->getWhereMany(['therapist_id' => $therapistId, 'therapy_id' => $selectedTherapy]);
                    if (empty($exists) || $exists->isEmpty()) {
                        $validateDataTherapies[$index] = [
                            'therapist_id' => $therapistId,
                            'therapy_id'   => $selectedTherapy
                        ];
                        $validateDataTherapies[$index]['created_at'] = $now;
                        $validateDataTherapies[$index]['updated_at'] = $now;
                        $validate = $this->therapistSelectedTherapyRepo->validate($validateDataTherapies[$index]);
                        if ($validate['is_validate'] == '0') {
                            $this->errorMsg[] = $validate['msg'];
                            break;
                        }
                    }
                }
            }

            if ($this->isErrorFree()) {
                unset($data['profile_photo']);
                unset($data['selected_massages']);
                unset($data['selected_therapies']);

                if (!empty($request->profile_photo)) {
                    $fileName               = $request->profile_photo->getClientOriginalName();
                    $storeFile              = $request->profile_photo->storeAs($this->profilePhotoPath, $fileName);
                    $data['profile_photo']  = $fileName;
                }

                if (!empty($validateDataMassages)) {
                    $this->therapistSelectedMassageRepo->create($validateDataMassages, true);
                }

                if (!empty($validateDataTherapies)) {
                    $this->therapistSelectedTherapyRepo->create($validateDataTherapies, true);
                }

                $isUpdate = $this->therapist->where('id', $therapistId)->update($data);
                if ($isUpdate) {
                    // $therapist = $this->getWhereFirst('id', $therapistId);
                    $therapist = $this->getGlobalResponse($therapistId);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        if (!$this->isErrorFree()) {
            return response()->json([
                'code' => 401,
                'msg'  => $this->errorMsg
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist profile updated successfully !',
            'data' => $therapist
        ]);
    }

    public function signIn(array $data)
    {
        $email    = (!empty($data['email'])) ? $data['email'] : NULL;
        $password = (!empty($data['password'])) ? $data['password'] : NULL;

        if (empty($email)) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide email properly.'
            ]);
        } elseif (empty($password)) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide password properly.'
            ]);
        }

        if (!empty($email) && !empty($password)) {
            $getTherapist = $this->getWhereMany(['email' => $email, 'is_freelancer' => $this->isFreelancer]);

            if (!empty($getTherapist[0]) && Hash::check($password, $getTherapist[0]->password)) {
                $getTherapist = $getTherapist->first();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Therapist found successfully !',
                    'data' => $this->getGlobalResponse($getTherapist->id)
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Therapist email or password seems wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Something went wrong.'
        ]);
    }

    public function delete(int $id)
    {}

    public function search(array $data, int $limit = 10)
    {
        $query   = (!empty($data['q'])) ? $data['q'] : "";
        $limit   = (!is_integer($limit)) ? 10 : $limit;
        $now     = Carbon::now();
        $nowDate = $now->toDateString();

        $tableTherapist       = $this->therapist->getTableName();
        $tableTherapistReview = $this->therapistReview->getTableName();
        $tableBookingInfo     = $this->bookingInfo->getTableName();
        $getData              = $this->therapist
                                    ->select(DB::raw("{$tableTherapist}.*, SUM(tr.rating) AS total_ratings, bi.id as booking_id"))
                                    ->leftJoin($tableTherapistReview . ' AS tr', "{$tableTherapist}.id", '=', 'tr.therapist_id')
                                    ->leftJoin($tableBookingInfo . ' AS bi', 'bi.therapist_id', '=', DB::raw("(SELECT bii.therapist_id FROM `booking_infos` AS bii WHERE bii.therapist_id = therapists.id AND bii.is_done = '0' AND bii.is_cancelled = '0' AND bii.massage_date = '{$nowDate}' GROUP BY bii.therapist_id)"))
                                    ->where(function ($qry) use ($query) {
                                        $qry->where("name", "LIKE", "%" . $query . "%")
                                            ->orWhere("email", "LIKE", "%" . $query . "%")
                                            ->orWhere("short_description", "LIKE", "%" . $query . "%");
                                    })
                                    ->where("is_freelancer", "=", $this->isFreelancer)
                                    ->groupBy("{$tableTherapist}.id")
                                    ->havingRaw("booking_id IS NULL")
                                    ->orderBy(DB::raw('SUM(tr.rating)'), 'desc')
                                    ->limit($limit)
                                    ->get();

        if (!empty($getData) && !$getData->isEmpty()) {
            $getData->map(function($value, $key) use($getData, $nowDate) {
                // Check whoes earned less per hour for this day.
                $value->total_earned = 0;
                $bookingInfos        = $this->bookingInfo
                                     ->with('booking')
                                     ->where('therapist_id', $value->id)
                                     ->where('is_done', '1')
                                     ->where('is_cancelled', '0')
                                     ->whereRaw("DATE(`massage_date`) = '{$nowDate}'")
                                     ->get();
                if (!empty($bookingInfos) && !$bookingInfos->isEmpty()) {
                    $bookingInfos->each(function($bookingInfo, $index) use($value, &$earningInfo) {
                        $paidPercentage     = $value->paid_percentage;
                        $finalPrice         = ($bookingInfo->price - $bookingInfo->cost);
                        $totalEarned        = (($paidPercentage / 100) * $finalPrice);
                        $totalEarnedAsShop  = $this->currencyHelper->convertToDefaultShopCurrency($bookingInfo->booking->user_id, $totalEarned, $bookingInfo->booking_currency_id);

                        if (!isset($earningInfo[$value->id])) {
                            $earningInfo[$value->id]['totalEarned'] = 0;
                        }
                        $value->total_earned += $totalEarnedAsShop;
                    });
                }

                unset($value->booking_id);
            });
            $getData = $getData->sortBy(function($value, $key) {
                return $value->total_earned;
            });

            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist found successfully !',
                'data' => $getData
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist doesn\'t found.'
        ]);
    }

    public function reviewCount(TherapistReview $collection)
    {
        if (!empty($collection) && $collection instanceof TherapistReview) {
            $ratings = [];

            $collection->each(function($value) use(&$ratings) {
                $ratings[$value->therapist_id][] = $value->rating;
            });

            return array_map('array_sum', $ratings);
        }

        return false;
    }

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapist = $this->therapist->find($id);

        if (!empty($therapist)) {
            return $therapist->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }

    public function verifyMobile(int $id, array $data)
    {
        /* TODO all things like email otp after get sms gateway. */
        $this->successMsg = "SMS sent successfully !";

        return $this;
    }

    public function compareOtpSms(int $therapistId, array $data)
    {
        /* TODO all things like email otp compare after get sms gateway. */
        $otp = (!empty($data['otp'])) ? $data['otp'] : NULL;

        if ($otp == '1234') {
            $this->therapist->where(['id' => $therapistId])->update(['is_mobile_verified' => '1']);
            $this->successMsg = "OTP matched successfully !";
        } else {
            $this->errorMsg[] = "OTP seems wrong.";
        }

        return $this;
    }

    public function verifyEmail(int $id, array $data)
    {
        $getTherapist = $this->getWhereFirst('id', $id);

        if (!empty($getTherapist)) {
            $emailId = (!empty($data['email'])) ? $data['email'] : NULL;

            // Validate
            $data = [
                'therapist_id' => $id,
                'otp'          => 1434,
                'email'        => $emailId,
                'is_send'      => '0'
            ];
            $validator = $this->therapistEmailOtpRepo->validate($data);
            if ($validator['is_validate'] == '0') {
                $this->errorMsg[] = $validator['msg'];
                return $this;
            }

            if ($emailId == $getTherapist->email && $getTherapist->is_email_verified == '1') {
                $this->errorMsg[] = "This therapist email already verified with this {$emailId} email id !";
                return $this;
            }

            /* $validate = (filter_var($emailId, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $emailId));
            if (!$validate) {
                $this->errorMsg[] = "Please provide valid email id.";
            } */

            if ($this->isErrorFree()) {
                $sendOtp         = $this->emailRepo->sendOtp($emailId);
                $data['otp']     = NULL;
                $data['is_send'] = '0';
                if ($this->getJsonResponseCode($sendOtp) == '200') {
                    $data['is_send']     = '1';
                    $data['is_verified'] = '0';
                    $data['otp']         = $this->getJsonResponseOtp($sendOtp);
                    $this->successMsg    = $this->getJsonResponseMsg($sendOtp);
                } else {
                    $this->errorMsg[] = $this->getJsonResponseMsg($sendOtp);
                }
                $getData = $this->therapistEmailOtpRepo->getWhereMany(['therapist_id' => $id]);
                if (!empty($getData) && !$getData->isEmpty()) {
                    $this->therapistEmailOtpRepo->updateOtp($id, $data);
                } else {
                    $this->therapistEmailOtpRepo->create($data);
                }
            }
        } else {
            $this->errorMsg[] = "Please provide valid therapist id.";
        }

        return $this;
    }

    public function compareOtpEmail(int $therapistId, array $data)
    {
        $otp = (!empty($data['otp'])) ? $data['otp'] : NULL;

        if (empty($otp)) {
            $this->errorMsg[] = "Please provide OTP properly.";
        }

        if ($this->isErrorFree()) {
            if (strtolower(env('APP_ENV') != 'live') && $otp == '1234') {
                $getTherapist = $this->therapistEmailOtpRepo->getWhereMany(['therapist_id' => $therapistId]);
            } else {
                $getTherapist = $this->therapistEmailOtpRepo->getWhereMany(['therapist_id' => $therapistId, 'otp' => $otp]);
            }

            if (!empty($getTherapist) && !$getTherapist->isEmpty()) {
                $getTherapist = $getTherapist->first();
                if ($getTherapist->is_verified == '1') {
                    $this->errorMsg[] = "OTP already verified.";
                } else {
                    $this->therapist->where(['id' => $therapistId])->update(['email' => $getTherapist->email, 'is_email_verified' => '1']);
                    $this->therapistEmailOtpRepo->setIsVerified($getTherapist->id, '1');
                    $this->successMsg = "OTP matched successfully !";
                }
            } else {
                $this->errorMsg[] = "OTP seems wrong.";
            }
        }

        return $this;
    }

    public function isDocumentVerified(int $id, string $isVerified = '0')
    {
        $isVerified = (!in_array($isVerified, ['0', '1'])) ? '0' : $isVerified;

        return $this->therapist->where('id', $id)->update(['is_document_verified' => $isVerified]);
    }
}

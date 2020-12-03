<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\Massage\MassagePriceRepository;
use App\Repositories\Massage\MassageTimingRepository;
use App\Shop;
use App\SessionType;
use App\UserPeople;
use App\Booking;
use App\BookingInfo;
use App\BookingMassage;
use App\Massage;
use App\MassagePrice;
use App\MassageTiming;
use App\MassagePreferenceOption;
use Carbon\Carbon;
use CurrencyHelper;
use DB;

class BookingRepository extends BaseRepository
{
    protected $shop, $sessionType, $userPeople, $booking, $bookingInfo, $bookingMassage, $massage, $massagePrice, $massageTimingModel, $massagePriceModel, $massageTiming, $massagePreferenceOption, $currencyHelper;

    public function __construct()
    {
        parent::__construct();
        $this->shop           = new Shop();
        $this->sessionType    = new SessionType();
        $this->userPeople     = new UserPeople();
        $this->booking        = new Booking();
        $this->bookingInfo    = new BookingInfo();
        $this->bookingMassage = new BookingMassage();
        $this->massage        = new Massage();
        $this->massagePrice   = new MassagePriceRepository();
        $this->massageTimingModel = new MassageTiming();
        $this->massagePriceModel  = new MassagePrice();
        $this->massageTiming  = new MassageTimingRepository();
        $this->massagePreferenceOption = new MassagePreferenceOption();
        $this->currencyHelper = new CurrencyHelper();
    }

    public function create(array $data)
    {
        $booking = [];
        $now     = Carbon::now();
        DB::beginTransaction();

        try {
            $data = $this->buildPack($data);

            $validator = $this->booking->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $bookingType = $data['booking_type'];

            $bookingInfos = $data['booking_info'];
            $validator    = $this->bookingInfo->validator($bookingInfos);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $validator = $this->bookingMassage->validator($bookingInfos, true, $bookingType);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            if (isset($data['booking_type'])) {
                $data['booking_type'] = (string)$data['booking_type'];
            }
            if (isset($data['bring_table_futon'])) {
                $data['bring_table_futon'] = (string)$data['bring_table_futon'];
            }

            $booking                    = $this->booking;
            $booking->booking_type      = $data['booking_type'];
            $booking->special_notes     = (!empty($data['special_notes']) ? $data['special_notes'] : NULL);
            $booking->bring_table_futon = (isset($data['bring_table_futon']) ? (string)$data['bring_table_futon'] : $booking::$defaultTableFutons);
            $booking->user_id           = $data['user_id'];
            $booking->shop_id           = $data['shop_id'];

            $booking->fill($data);
            $booking->save();

            $bookingId         = $booking->id;
            $userId            = $data['user_id'];
            $shopId            = $data['shop_id'];
            $massageDate       = Carbon::createFromTimestamp($data['booking_date_time'])->toDate();
            $massageTime       = Carbon::createFromTimestamp($data['booking_date_time'])->toDateTime();
            $bookingInfos      = [];
            $shopCurrencyId    = $this->currencyHelper->getDefaultShopCurrency($shopId, true);
            $shopCurrency      = $this->currencyHelper->getCodeFromId($shopCurrencyId);
            $bookingCurrencyId = (!empty($data['currency_id'])) ? $data['currency_id'] : $shopCurrencyId;
            $bookingCurrency   = (!empty($bookingCurrencyId)) ? $this->currencyHelper->getCodeFromId($bookingCurrencyId) : NULL;
            $bookingCurrency   = (empty($bookingCurrency)) ? $shopCurrency : $bookingCurrency;
            $exchangeRate      = $this->currencyHelper->getRate($bookingCurrencyId);
            $bookingMassages   = [];

            foreach ((array)$data['booking_info'] as $index => $infos) {
                if (empty($infos['massage_info'])) {
                    continue;
                }

                $bookingInfo = new $this->bookingInfo;

                $bookingInfos[$index] = [
                    'location'              => $infos['location'],
                    'massage_date'          => $massageDate,
                    'massage_time'          => $massageTime,
                    'imc_type'              => $infos['imc_type'],
                    'booking_currency_id'   => $bookingCurrencyId,
                    'shop_currency_id'      => $shopCurrencyId,
                    'therapist_id'          => $infos['therapist_id'],
                    'booking_id'            => $bookingId,
                    'room_id'               => $infos['room_id'],
                    'user_people_id'        => $infos['user_people_id'],
                    'created_at'            => $now
                ];

                $bookingInfo->fill($bookingInfos[$index]);
                $bookingInfo->save();

                if ($bookingInfo) {
                    $bookingInfoId = $bookingInfo->id;
                }

                foreach ($infos['massage_info'] as $indexBookingMassage => $massageInfo) {
                    $bookingMassage  = new $this->bookingMassage;

                    $getMassagePrice = $this->massagePrice->getWhereFirst('id', $massageInfo['massage_prices_id']);

                    $bookingMassages[$indexBookingMassage] = [
                        'price'                 => $this->currencyHelper->convert($getMassagePrice->price, $exchangeRate, $bookingCurrencyId),
                        'cost'                  => $this->currencyHelper->convert($getMassagePrice->cost, $exchangeRate, $bookingCurrencyId),
                        'origional_price'       => $getMassagePrice->price,
                        'origional_cost'        => $getMassagePrice->cost,
                        'exchange_rate'         => $exchangeRate,
                        'notes_of_injuries'     => $massageInfo['notes_of_injuries'],
                        'pressure_preference'   => $massageInfo['pressure_preference'],
                        'gender_preference'     => $massageInfo['gender_preference'],
                        'focus_area_preference' => $massageInfo['focus_area_preference'],
                        'massage_timing_id'     => $getMassagePrice->massage_timing_id,
                        'massage_prices_id'     => $massageInfo['massage_prices_id'],
                        'booking_info_id'       => $bookingInfoId,
                        'created_at'            => $now
                    ];
                }

                $bookingMassage->insert($bookingMassages);
            }
            
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code'      => 200,
            'msg'       => 'Booking created successfully !',
            'bookingId' => $bookingId
        ]);
    }

    public function buildPack(array $data)
    {
        if (isset($data['is_pack']) && $data['is_pack'] == 1) {
            $data['shop_id'] = "";

            $datya['booking_info'] = [
                "user_people_id" => 2,
                "location"       => "Test Location",
                "imc_type"       => 1,
                "therapist_id"   => 2,
                "room_id"        => 1,
                "massage_info"   => [
                    "pressure_preference"   => 3,
                    "gender_preference"     => 5,
                    "focus_area_preference" => 31,
                    "notes_of_injuries"     => "No any injury.",
                    "massage_prices_id"     => 1
                ]
            ];
        }

        return $data;
    }

    public function all()
    {
        return $this->booking->all();
    }

    public function getWhere($column, $value)
    {
        return $this->booking->where($column, $value)->get();
    }

    public function getWherePastFuture($userId, $isPast = false, $isUpcoming = true, $isPending = false, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->select(DB::RAW($this->booking::getTableName() . '.id, massage_date, massage_time, ' . $this->booking::getTableName() . '.booking_type, ' . $this->shop::getTableName() . '.name as shop_name, ' . $this->shop::getTableName() . '.description as shop_description, ' . $this->sessionType::getTableName() . '.type as session_type, user_people_id, ' . $this->bookingInfo::getTableName() . '.id as bookingInfoId, ' . $this->userPeople::getTableName() . '.name as user_people_name, ' . $this->userPeople::getTableName() . '.age as user_people_age, ' . $this->userPeople::getTableName() . '.gender as user_people_gender, ' . $this->userPeople::getTableName() . '.photo as user_prople_photo'))
                         ->join($this->bookingInfo::getTableName(), $this->booking::getTableName() . '.id', '=', $this->bookingInfo::getTableName() . '.booking_id')
                         ->join($this->userPeople::getTableName(), $this->bookingInfo::getTableName() . '.user_people_id', '=', $this->userPeople::getTableName() . '.id')
                         ->leftJoin($this->shop::getTableName(), $this->booking::getTableName() . '.shop_id', '=', $this->shop::getTableName() . '.id')
                         ->leftJoin($this->sessionType::getTableName(), $this->booking::getTableName() . '.session_id', '=', $this->sessionType::getTableName() . '.id')
                         ->where($this->bookingInfo::getTableName() . '.massage_date', ($isPast === true ? '<' : '>='), $now);

        if (!empty($userId) && is_numeric($userId)) {
            $bookings->where($this->booking::getTableName() . '.user_id', $userId);
        }

        $bookings = $bookings->get();

        $returnBookings = [];
        if (!empty($bookings) && !$bookings->isEmpty()) {

            $userPeopleIds  = $bookings->pluck('user_people_id');
            $userPeoples    = $massagePrices = $bookingMassages = $massages = [];

            if (!empty($userPeopleIds) && !$userPeopleIds->isEmpty()) {
                $userPeoples = $this->userPeople->select('id', 'name', 'age', 'gender')->whereIn('id', array_unique($userPeopleIds->toArray()))->get();

                if (!empty($userPeoples) && !$userPeoples->isEmpty()) {
                    $userPeoples = $userPeoples->keyBy('id');
                }
            }

            $return = [];
            $bookings->map(function($data, $index) use(&$return) {
                $return[$data->id][] = $data;
            });

            foreach ($return as $bookingId => $datas) {
                $returnUserPeoples = [];

                foreach ($datas as $index => $data) {
                    $bookingInfoId = $data->bookingInfoId;
                    $userPeopleId  = $data->user_people_id;

                    $returnUserPeoples[$bookingId][$index] = [
                        'id'     => $userPeopleId,
                        'name'   => $data->user_people_name,
                        'age'    => $data->user_people_age,
                        'gender' => $data->user_people_gender,
                        'photo'  => $data->user_prople_photo
                    ];

                    $bookingMassages = $this->bookingMassage
                                            ->select($this->massage::getTableName() . '.name', $this->bookingMassage::getTableName() . '.price', $this->massageTimingModel::getTableName() . '.time')
                                            ->join($this->massagePriceModel::getTableName(), $this->bookingMassage::getTableName() . '.massage_prices_id', '=', $this->massagePriceModel::getTableName() . '.id')
                                            ->join($this->massageTimingModel::getTableName(), $this->massagePriceModel::getTableName() . '.massage_timing_id', '=', $this->massageTimingModel::getTableName() . '.id')
                                            ->join($this->massage::getTableName(), $this->massagePriceModel::getTableName() . '.massage_id', '=', $this->massage::getTableName() . '.id')
                                            ->where('booking_info_id', $bookingInfoId)
                                            ->get();

                    if (!empty($bookingMassages) && !$bookingMassages->isEmpty()) {
                        $returnUserPeoples[$bookingId][$index]['booking_massages'] = $bookingMassages;

                        if (isset($returnBookings[$bookingId]['total_price'])) {
                            $returnBookings[$bookingId]['total_price'] += $bookingMassages->sum('price');
                        } else {
                            $returnBookings[$bookingId]['total_price'] = $bookingMassages->sum('price');
                        }
                    }

                    $returnBookings[$bookingId] = [
                        'id'               => $bookingId,
                        'booking_type'     => $data->booking_type,
                        'shop_name'        => $data->shop_name,
                        'shop_description' => $data->shop_description,
                        'session_type'     => $data->session_type,
                        'massage_date'     => $data->massage_date,
                        'massage_time'     => $data->massage_time,
                        'total_price'      => number_format($returnBookings[$bookingId]['total_price'], 2)
                    ];
                }

                $returnBookings[$bookingId]['user_people'] = $returnUserPeoples[$bookingId];
            }

            $returnBookings = array_values($returnBookings);
        }

        if ($isApi === true) {
            $messagePrefix = (($isPast) ? 'Past' : 'Future');
            if (!empty($returnBookings)) {
                $message = $messagePrefix . ' booking found successfully !';
            } else {
                $message = $messagePrefix . ' booking not found !';
            }

            return response()->json([
                'code' => 200,
                'msg'  => $message,
                'data' => $returnBookings
            ]);
        }

        return $bookings;
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $bookingData = $this->booking->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Booking found successfully !',
                'data' => $bookingData
            ]);
        }

        return $bookingData;
    }

    public function update(int $bookingInfoId, array $data)
    {
        $update          = false;
        $findBookingInfo = $this->getInfos($bookingInfoId);

        if (!empty($findBookingInfo)) {
            $data['booking_info']['booking_id'] = $findBookingInfo->booking_id;

            DB::beginTransaction();

            try {
                $validator = $this->booking->validator($data, true);
                if (!$validator->fails()) {
                    $validator = $this->bookingInfo->validator($data['booking_info']);
                }
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->bookingInfo->where('id', $bookingInfoId)->update($data['booking_info']);
                if ($update) {
                    unset($data['booking_info']);
                    $update = $this->booking->where('id', $findBookingInfo->booking_id)->update($data);
                    // $getBooking = $this->get($findBookingInfo->booking_id);
                }
            } catch (Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Booking updated successfully !'
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
            'msg'  => 'Booking not found.'
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $booking = $this->booking->find($id);

        if (!empty($booking)) {
            return $booking->get();
        }

        return NULL;
    }

    public function getInfos(int $id)
    {
        $getInfos = $this->bookingInfo->find($id);

        if (!empty($getInfos)) {
            return $getInfos->first();
        }

        return NULL;
    }

    public function updateBooking(int $id, array $data)
    {
        $update      = false;
        $findBooking = $this->getWhereFirst('id', $id);

        if (!empty($findBooking)) {
            DB::beginTransaction();

            try {
                foreach ($this->booking->getFillable() as $fillable) {
                    $data[$fillable] = (!empty($data[$fillable]) ? $data[$fillable] : $findBooking->{$fillable});
                }

                $validator = $this->booking->validator($data, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->booking->where('id', $id)->update($data);
            } catch(Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Booking updated successfully !'
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
            'msg'  => 'Booking not found.'
        ]);
    }

    public function setMassageDone(int $bookingInfoId)
    {
        return $this->bookingInfo->where('id', $bookingInfoId)->update(['is_done' => '1']);
    }

    public function addRoom(int $bookingInfoId, int $roomId)
    {
        $findBookingInfo = $this->getInfos($bookingInfoId);

        if (!empty($findBookingInfo)) {
            $isUpdateRoom = $this->bookingInfo->where('id', $bookingInfoId)->update(['room_id' => $roomId]);

            if ($isUpdateRoom) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Room added successfully !'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Booking info not found.'
        ]);
    }

    public function getBookingPlaces($userId)
    {
        if (!empty($userId)) {
            $response = [];
            $bookings = $this->booking->where('user_id', (int)$userId)->groupBy('shop_id')->get();

            if (!empty($bookings) && !$bookings->isEmpty()) {
                foreach ($bookings as $key => $booking) {
                    if (!empty($booking->shop)) {
                        $booking->shop->total_services = $booking->shop->massages->count();

                        $response[] = $booking->shop;
                    }
                }
            }

            if (!empty($response)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Booking places found successfully !',
                    'data' => $response
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Booking places not found.'
        ]);
    }

    public function getBookingTherapists($userId)
    {
        if (!empty($userId)) {
            $response = [];
            $bookings = $this->booking;

            if (env('APP_ENV', '') == 'dev') {
                $bookings = $bookings->get();
            } else {
                $bookings = $bookings->where('user_id', (int)$userId)->get();
            }

            if (!empty($bookings) && !$bookings->isEmpty()) {
                foreach ($bookings as $key => $booking) {
                    if (!empty($booking->bookingInfo)) {
                        foreach ($booking->bookingInfo as $bookingInfo) {
                            if (!empty($bookingInfo->therapist)) {
                                $therapistId = $bookingInfo->therapist->id;

                                $response[$therapistId] = $bookingInfo->therapist;
                            }
                        }
                    }
                }
            }

            if (!empty($response)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Booking therapists found successfully !',
                    'data' => array_values($response)
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Booking therapists not found.'
        ]);
    }

    public function getGlobalResponse(int $bookingInfoId, $isApi = false)
    {
        $bookings = $this->booking
                         ->select(
                                DB::RAW(
                                    $this->bookingInfo::getTableName() . '.id as booking_info_id, '. 
                                    $this->sessionType::getTableName() . '.type as session_type, ' . 
                                    $this->massage::getTableName() . '.name as service_name, UNIX_TIMESTAMP(' . 
                                    $this->bookingInfo::getTableName() . '.massage_date) * 1000 as massage_date, UNIX_TIMESTAMP(' . 
                                    $this->bookingInfo::getTableName() . '.massage_time) * 1000 as massage_time, ' . 
                                    'gender.name as gender_preference, ' . 
                                    'pressure.name as pressure_preference, ' . 
                                    $this->booking::getTableName() . '.special_notes as notes, ' . 
                                    $this->bookingMassage::getTableName() . '.notes_of_injuries as injuries, ' . 
                                    'focus_area.name as focus_area, ' . 
                                    $this->booking::getTableName() . '.table_futon_quantity'
                                )
                         )
                         ->join($this->bookingInfo::getTableName(), $this->booking::getTableName() . '.id', '=', $this->bookingInfo::getTableName() . '.booking_id')
                         ->join($this->userPeople::getTableName(), $this->bookingInfo::getTableName() . '.user_people_id', '=', $this->userPeople::getTableName() . '.id')
                         ->leftJoin($this->sessionType::getTableName(), $this->booking::getTableName() . '.session_id', '=', $this->sessionType::getTableName() . '.id')
                         ->leftJoin($this->bookingMassage::getTableName(), $this->bookingInfo::getTableName() . '.id', '=', $this->bookingMassage::getTableName() . '.booking_info_id')
                         ->leftJoin($this->massagePriceModel::getTableName(), $this->bookingMassage::getTableName() . '.massage_prices_id', '=', $this->massagePriceModel::getTableName() . '.id')
                         ->leftJoin($this->massage::getTableName(), $this->massagePriceModel::getTableName() . '.massage_id', '=', $this->massage::getTableName() . '.id')
                         ->leftJoin($this->massagePreferenceOption::getTableName() . ' as gender', $this->bookingMassage::getTableName() . '.gender_preference', '=', 'gender.id')
                         ->leftJoin($this->massagePreferenceOption::getTableName() . ' as pressure', $this->bookingMassage::getTableName() . '.pressure_preference', '=', 'pressure.id')
                         ->leftJoin($this->massagePreferenceOption::getTableName() . ' as focus_area', $this->bookingMassage::getTableName() . '.focus_area_preference', '=', 'focus_area.id')
                         ->where($this->bookingInfo::getTableName() . '.id', $bookingInfoId)
                         ->get();

        if ($isApi) {
            if (!empty($bookings) && !$bookings->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'Booking found successfully !',
                    'data' => $bookings
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Booking doesn\'t found.',
                'data' => []
            ]);
        }

        return $bookings;
    }

    public function errors()
    {}
}

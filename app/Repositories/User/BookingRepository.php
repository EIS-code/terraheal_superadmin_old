<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\Massage\MassagePriceRepository;
use App\Repositories\Massage\MassageTimingRepository;
use App\Booking;
use App\BookingInfo;
use App\BookingMassage;
use Carbon\Carbon;
use CurrencyHelper;
use DB;

class BookingRepository extends BaseRepository
{
    protected $booking, $bookingInfo, $bookingMassage, $massagePrice, $massageTiming, $currencyHelper;

    public function __construct()
    {
        parent::__construct();
        $this->booking        = new Booking();
        $this->bookingInfo    = new BookingInfo();
        $this->bookingMassage = new BookingMassage();
        $this->massagePrice   = new MassagePriceRepository();
        $this->massageTiming  = new MassageTimingRepository();
        $this->currencyHelper = new CurrencyHelper();
    }

    public function create(array $data)
    {
        $booking = [];
        $now     = Carbon::now();
        DB::beginTransaction();

        try {
            $validator = $this->booking->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $bookingInfos = $data['booking_info'];
            $validator    = $this->bookingInfo->validator($bookingInfos);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $validator = $this->bookingMassage->validator($bookingInfos, true);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }


            $booking                = $this->booking;
            $booking->booking_type  = $data['booking_type'];
            $booking->special_notes = (!empty($data['special_notes']) ? $data['special_notes'] : NULL);
            $booking->user_id       = $data['user_id'];
            $booking->shop_id       = $data['shop_id'];

            $booking->fill($data);
            $booking->save();

            $bookingId         = $booking->id;
            $userId            = $data['user_id'];
            $massageDate       = Carbon::createFromTimestamp($data['booking_date_time'])->toDate();
            $massageTime       = Carbon::createFromTimestamp($data['booking_date_time'])->toDateTime();
            $bookingInfos      = [];
            $shopCurrencyId    = $this->currencyHelper->getDefaultShopCurrency($userId, true);
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
                    'bring_table_futon'     => (isset($infos['bring_table_futon'])) ? (string)$infos['bring_table_futon'] : $this->bookingInfo::DEFAULT_BRING_TABLE_FUTON,
                    'table_futon_quantity'  => (isset($infos['table_futon_quantity'])) ? $infos['table_futon_quantity'] : $this->bookingInfo::DEFAULT_TABLE_FUTON_QUANTITY,
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
                        'price'             => $this->currencyHelper->convert($getMassagePrice->price, $exchangeRate, $bookingCurrencyId),
                        'cost'              => $this->currencyHelper->convert($getMassagePrice->cost, $exchangeRate, $bookingCurrencyId),
                        'origional_price'   => $getMassagePrice->price,
                        'origional_cost'    => $getMassagePrice->cost,
                        'exchange_rate'     => $exchangeRate,
                        'preference'        => $massageInfo['preference'],
                        'notes_of_injuries' => $massageInfo['notes_of_injuries'],
                        'massage_preference_option_id' => $massageInfo['massage_preference_option_id'],
                        'massage_timing_id' => $getMassagePrice->massage_timing_id,
                        'massage_prices_id' => $massageInfo['massage_prices_id'],
                        'booking_info_id'   => $bookingInfoId,
                        'created_at'        => $now
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

    public function all()
    {
        return $this->booking->all();
    }

    public function getWhere($column, $value)
    {
        return $this->booking->where($column, $value)->get();
    }

    public function getWherePastFuture(int $userId, $isPast = false, $isUpcoming = true, $isPending = false, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->where('user_id', $userId)
                         ->with(['bookingInfo' => function($query) use($now, $isPast) {
                                $query->with('bookingMassages')
                                      ->where('massage_date', ($isPast === true ? '<' : '>='), $now);
                         }])
                         ->get();
        /* $bookings = $this->booking
                         ->join('booking_infos', 'bookings.id', '=', 'booking_infos.booking_id')
                         ->where('user_id', $userId)
                         ->get(); */

        if (!empty($bookings) && !$bookings->isEmpty()) {
            $bookings->map(function($data, $index) use($bookings) {
                if (empty($data->bookingInfo) || $data->bookingInfo->isEmpty()) {
                    unset($bookings[$index]);
                }
            });
        }

        if ($isApi === true) {
            $messagePrefix = (($isPast) ? 'Past' : 'Future');
            if (!empty($bookings) && !$bookings->isEmpty()) {
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
                    'code' => 401,
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
            $bookings = $this->booking->where('user_id', (int)$userId)->get();

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
                    'code' => 401,
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

    public function errors()
    {}
}

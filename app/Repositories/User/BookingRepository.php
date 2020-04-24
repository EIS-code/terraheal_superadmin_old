<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\Massage\MassagePriceRepository;
use App\Repositories\Massage\MassageTimingRepository;
use App\Booking;
use App\BookingInfo;
use Carbon\Carbon;
use DB;

class BookingRepository extends BaseRepository
{
    protected $booking, $bookingInfo, $massagePrice, $massageTiming;

    public function __construct()
    {
        parent::__construct();
        $this->booking       = new Booking();
        $this->bookingInfo   = new BookingInfo();
        $this->massagePrice  = new MassagePriceRepository();
        $this->massageTiming = new MassageTimingRepository();
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

            $booking                = $this->booking;
            $booking->booking_type  = $data['booking_type'];
            $booking->special_notes = (!empty($data['special_notes']) ? $data['special_notes'] : NULL);
            $booking->total_persons = $data['total_persons'];
            $booking->user_id       = $data['user_id'];

            // $booking->fill($data);
            $booking->save();

            $bookingInfo  = $this->bookingInfo;
            $bookingInfos = [];
            foreach ((array)$data['booking_info'] as $infos) {
                $infos['booking_id'] = $booking->id;

                $validator = $this->bookingInfo->validator($infos);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                // Get massage pricing.
                $getMassagePrice = $this->massagePrice->getWhereFirst('id', $infos['massage_prices_id']);
                if (empty($getMassagePrice)) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Massage price doesn\'t found.'
                    ]);
                }

                // Get massage timing.
                $getMassageTiming = $this->massageTiming->getWhereFirst('id', $infos['massage_timing_id']);
                if (empty($getMassageTiming)) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Massage duration doesn\'t found.'
                    ]);
                }

                $bookingInfos[] = [
                    'preference'            => $infos['preference'],
                    'location'              => $infos['location'],
                    'massage_date'          => $infos['massage_date'],
                    'massage_time'          => $infos['massage_time'],
                    'notes_of_injuries'     => $infos['notes_of_injuries'],
                    'imc_type'              => $infos['imc_type'],
                    'massage_timing'        => $getMassageTiming->time,
                    'massage_pricing'       => $getMassagePrice->price,
                    // 'copy_with_id'          => $infos['copy_with_id'],
                    'massage_timing_id'     => $infos['massage_timing_id'],
                    'therapist_id'          => $infos['therapist_id'],
                    'massage_prices_id'     => $infos['massage_prices_id'],
                    'booking_id'            => $infos['booking_id'],
                    'room_id'               => $infos['room_id'],
                    'created_at'            => $now
                ];
            }
            $bookingInfo->insert($bookingInfos);
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Booking created successfully !',
            'data' => $booking
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

    public function getWherePastFuture(int $userId, $isPast = true, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->where('user_id', $userId)
                         ->with(['bookingInfo' => function($query) use($now, $isPast) {
                                $query->where('massage_date', ($isPast === true ? '<' : '>='), $now);
                         }])
                         ->get();
        /* $bookings = $this->booking
                         ->join('booking_infos', 'bookings.id', '=', 'booking_infos.booking_id')
                         ->where('user_id', $userId)
                         ->get(); */

        if ($isApi === true) {
            $messagePrefix = (($isPast) ? 'Past' : 'Future');
            if (!empty($bookings)) {
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

    public function errors()
    {}
}

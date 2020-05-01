<?php

namespace App\Repositories\Therapist\Massage;

use App\Repositories\BaseRepository;
use App\TherapistMassageHistory;
use App\Repositories\User\BookingRepository;
use Carbon\Carbon;
use DB;

class TherapistMassageHistoryRepository extends BaseRepository
{
    protected $therapistMassageHistory, $booking;

    public function __construct()
    {
        parent::__construct();
        $this->therapistMassageHistory = new therapistMassageHistory();
        $this->booking = new BookingRepository();
    }

    public function startMassage(int $bookingInfoId)
    {
        $therapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();
/*
        $getBookingInfo = $this->booking->getInfos($bookingInfoId);
        $massageTime    = !empty($getBookingInfo) ? $getBookingInfo->massage_timing : 0;

        if (empty($massageTime)) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage duration not found !'
            ]);
        }
*/
        try {
            $data['start_time']      = $now;
            $therapistMassageHistory = $this->therapistMassageHistory;

            // If already exists then update it.
            $getMassageHistory = $this->getWhere('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory) && !$getMassageHistory->isEmpty()) {
                $therapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
            } else {
                $data['date']             = $now->toDateString();
                $data['booking_infos_id'] = $bookingInfoId;
                $therapistMassageHistory->fill($data);
                $therapistMassageHistory->save();
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage countdown started successfully !',
            'time' => $now
        ]);
    }

    public function completeMassage(int $bookingInfoId)
    {
        $therapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();

        try {
            $getMassageHistory = $this->getWhereFirst('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory)) {
                $getBookingInfo = $this->booking->getInfos($bookingInfoId);
                $massageTime    = !empty($getBookingInfo) ? $getBookingInfo->massage_timing : 0;

                if (empty($massageTime)) {
                    return response()->json([
                        'code' => 200,
                        'msg'  => 'Massage duration not found ! Please check booking.'
                    ]);
                }


                $therapistMassageHistory = $this->therapistMassageHistory;
                $createdDate            = new Carbon(date('Y-m-d', strtotime($getMassageHistory->date)) . ' ' . $getMassageHistory->start_time);
                $remainingTime          = $now->diff($createdDate)->format('%H:%I:%S');
                $data['end_time']       = $now;
                $data['remaining_time'] = $remainingTime;
                $isUpdate = $therapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);

                if ($isUpdate) {
                    $isUpdate = $this->booking->setMassageDone($bookingInfoId);

                    if (!$isUpdate) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => 'Something went wrong. This massage not done yet please retry again or contact supervisor !'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'This massage not started yet or not found !'
                ]);
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage completed successfully !',
            'time' => $getMassageHistory->fresh()
        ]);
    }

    public function pauseMassage($bookingInfoId)
    {
        $therapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();

        try {
            $getMassageHistory = $this->getWhereFirst('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory)) {
                $therapistMassageHistory = $this->therapistMassageHistory;
                $data['pause_from'] = $now;
                $therapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'This massage not started yet or not found !'
                ]);
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage pause successfully !',
            'time' => $now
        ]);
    }

    public function restartMassage($bookingInfoId)
    {
        $therapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();

        try {
            $getMassageHistory = $this->getWhereFirst('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory)) {
                $therapistMassageHistory = $this->therapistMassageHistory;
                $data['pause_to'] = $now;
                $therapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'This massage not started yet or not found !'
                ]);
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage restarted successfully !',
            'time' => $now
        ]);
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->therapistMassageHistory->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistMassageHistory->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistMassageHistory->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist massage history found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapistMassageHistory = $this->therapistMassageHistory->find($id);

        if (!empty($therapistMassageHistory)) {
            return $therapistMassageHistory->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

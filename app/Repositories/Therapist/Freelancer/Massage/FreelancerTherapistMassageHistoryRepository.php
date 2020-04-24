<?php

namespace App\Repositories\Therapist\Freelancer\Massage;

use App\Repositories\BaseRepository;
use App\FreelancerTherapistMassageHistory;
use App\Repositories\User\BookingRepository;
use Carbon\Carbon;
use DB;

class FreelancerTherapistMassageHistoryRepository extends BaseRepository
{
    protected $freelancerTherapistMassageHistory, $booking;

    public function __construct()
    {
        parent::__construct();
        $this->freelancerTherapistMassageHistory = new FreelancerTherapistMassageHistory();
        $this->booking = new BookingRepository();
    }

    public function startMassage(int $bookingInfoId)
    {
        $freelancerTherapistMassageHistory = [];
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
            $data['start_time']                = $now;
            $freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistory;

            // If already exists then update it.
            $getMassageHistory = $this->getWhere('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory) && !$getMassageHistory->isEmpty()) {
                $freelancerTherapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
            } else {
                $data['booking_infos_id'] = $bookingInfoId;
                $freelancerTherapistMassageHistory->fill($data);
                $freelancerTherapistMassageHistory->save();
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
        $freelancerTherapistMassageHistory = [];
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


                $freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistory;
                $createdDate            = new Carbon(date('Y-m-d', strtotime($getMassageHistory->created_at)) . ' ' . $getMassageHistory->start_time);
                $remainingTime          = $now->diff($createdDate)->format('%H:%I:%S');
                $data['end_time']       = $now;
                $data['remaining_time'] = $remainingTime;
                $isUpdate = $freelancerTherapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);

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
        $freelancerTherapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();

        try {
            $getMassageHistory = $this->getWhereFirst('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory)) {
                $freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistory;
                $data['pause_from'] = $now;
                $freelancerTherapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
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
        $freelancerTherapistMassageHistory = [];
        $now = Carbon::now();
        DB::beginTransaction();

        try {
            $getMassageHistory = $this->getWhereFirst('booking_infos_id', $bookingInfoId);
            if (!empty($getMassageHistory)) {
                $freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistory;
                $data['pause_to'] = $now;
                $freelancerTherapistMassageHistory->where('booking_infos_id', $bookingInfoId)->update($data);
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
        return $this->freelancerTherapistMassageHistory->all();
    }

    public function getWhere($column, $value)
    {
        return $this->freelancerTherapistMassageHistory->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->freelancerTherapistMassageHistory->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Freelancer therapist massage history found successfully !',
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
        $freelancerTherapistMassageHistory = $this->freelancerTherapistMassageHistory->find($id);

        if (!empty($freelancerTherapistMassageHistory)) {
            return $freelancerTherapistMassageHistory->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

<?php

namespace App\Repositories\Receptionist;

use App\Repositories\BaseRepository;
// use App\Receptionist;
use App\Booking;
use Carbon\Carbon;
use DB;

class ReceptionistRepository extends BaseRepository
{
    protected $receptionist, $booking;

    public function __construct()
    {
        parent::__construct();
        // $this->receptionist = new Receptionist();
        $this->booking      = new Booking();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->receptionist->all();
    }

    public function getWhere($column, $value)
    {
        return $this->receptionist->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $receptionistData = $this->receptionist->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Receptionist found successfully !',
                'data' => $receptionistData
            ]);
        }

        return $receptionistData;
    }

    public function getWherePastFuture(int $shopId, $isPast = true, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->with(['user' => function($query) use($shopId) {
                                $query->where('shop_id', $shopId);
                         }])
                         ->with(['bookingInfo' => function($query) use($now, $isPast) {
                                $query->where('massage_date', ($isPast === true ? '<' : '>='), $now);
                         }])
                         ->get();

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
    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $receptionist = $this->receptionist->find($id);

        if (!empty($receptionist)) {
            return $receptionist->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

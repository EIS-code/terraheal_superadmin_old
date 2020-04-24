<?php

namespace App\Repositories\Receptionist;

use App\Repositories\BaseRepository;
use App\Receptionist;
use App\Booking;
use Carbon\Carbon;
use DB;

class ReceptionistRepository extends BaseRepository
{
    protected $receptionist, $booking;

    public function __construct()
    {
        parent::__construct();
        $this->receptionist = new Receptionist();
        $this->booking      = new Booking();
    }

    public function create(array $data)
    {
        $receptionist = [];
        DB::beginTransaction();

        try {
            $validator = $this->receptionist->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $receptionist = $this->receptionist;
            $receptionist->fill($data);
            $receptionist->save();
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Receptionist created successfully !',
            'data' => $receptionist
        ]);
    }

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

    public function getWherePastFutureToday(int $shopId, $type = 'today', $isDone = false, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->with(['user' => function($query) use($shopId) {
                                $query->where('shop_id', $shopId);
                         }])
                         ->with(['bookingInfo' => function($query) use($now, $type, $isDone) {
                                if ($isDone === true) {
                                    $query->where('is_done', '=', '1');
                                }
                                $query->where('massage_date', ($type === 'today' ? '=' : ($type === 'past' ? '<' : '>=')), $now->toDateString());
                         }])
                         ->get();

        if ($isApi === true) {
            $messagePrefix = ($type === 'today' ? 'Today' : (($type === 'past') ? 'Past' : 'Future'));
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

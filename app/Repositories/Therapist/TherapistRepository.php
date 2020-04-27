<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\TherapistDocumentRepository;
use App\Therapist;
use App\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class TherapistRepository extends BaseRepository
{
    protected $therapist, $therapistDocumentRepo, $booking;

    public function __construct()
    {
        parent::__construct();
        $this->therapist              = new Therapist();
        $this->therapistDocumentRepo  = new therapistDocumentRepository();
        $this->booking                          = new Booking();
    }

    public function create(array $data)
    {
        $therapist = [];
        DB::beginTransaction();

        try {
            $validator = $this->therapist->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $therapist = $this->therapist;
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
            'data' => $therapist
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

    public function getWherePastFuture(int $therapistId, $isPast = true, $isApi = false)
    {
        $now = Carbon::now();

        $bookings = $this->booking
                         ->with(['bookingInfo' => function($query) use($therapistId, $now, $isPast) {
                                $query->where('massage_date', ($isPast === true ? '<' : '>='), $now)
                                      ->where('therapist_id', $therapistId);
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

    public function getWhereFirst($column, $value, $isApi = false)
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

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

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
}

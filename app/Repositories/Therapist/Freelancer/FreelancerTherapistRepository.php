<?php

namespace App\Repositories\Therapist\Freelancer;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\Freelancer\FreelancerTherapistDocumentRepository;
use App\FreelancerTherapist;
use App\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class FreelancerTherapistRepository extends BaseRepository
{
    protected $freelancerTherapist, $freelancerTherapistDocumentRepo, $booking;

    public function __construct()
    {
        parent::__construct();
        $this->freelancerTherapist              = new FreelancerTherapist();
        $this->freelancerTherapistDocumentRepo  = new FreelancerTherapistDocumentRepository();
        $this->booking                          = new Booking();
    }

    public function create(array $data)
    {
        $freelancerTherapist = [];
        DB::beginTransaction();

        try {
            $validator = $this->freelancerTherapist->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $freelancerTherapist = $this->freelancerTherapist;
            $freelancerTherapist->fill($data);
            if ($freelancerTherapist->save() && !empty($data['documents'])) {
                $this->freelancerTherapistDocumentRepo->create($data['documents'], $freelancerTherapist->id);
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Freelancer therapist created successfully !',
            'data' => $freelancerTherapist
        ]);
    }

    public function all()
    {
        return $this->freelancerTherapist->all();
    }

    public function getWhere($column, $value)
    {
        return $this->freelancerTherapist->where($column, $value)->get();
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
        $data = $this->freelancerTherapist->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Freelancer therapist found successfully !',
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
        $freelancerTherapist = $this->freelancerTherapist->find($id);

        if (!empty($freelancerTherapist)) {
            return $freelancerTherapist->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

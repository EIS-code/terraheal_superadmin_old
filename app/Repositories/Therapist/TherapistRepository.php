<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\TherapistDocumentRepository;
use App\Repositories\Therapist\TherapistReviewRepository;
use App\Therapist;
use App\TherapistReview;
use App\Booking;
use App\BookingInfo;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class TherapistRepository extends BaseRepository
{
    protected $therapist, $therapistDocumentRepo, $therapistReviewRepository, $booking, $bookingInfo;
    public    $isFreelancer = '0';

    public function __construct()
    {
        parent::__construct();
        $this->therapist                 = new Therapist();
        $this->therapistDocumentRepo     = new therapistDocumentRepository();
        $this->therapistReviewRepository = new TherapistReviewRepository();
        $this->therapistReview           = new TherapistReview();
        $this->booking                   = new Booking();
        $this->bookingInfo               = new BookingInfo();
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
                                    ->leftJoin($tableBookingInfo . ' AS bi', 'bi.therapist_id', '=', DB::raw("(SELECT bii.therapist_id FROM `booking_infos` AS bii WHERE bii.therapist_id = therapists.id AND bii.is_done = '0' AND bii.massage_date = '{$nowDate}' GROUP BY bii.therapist_id)"))
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
        /* 
        
        $getData = DB::select("SELECT * FROM (
                                    SELECT tp.*, SUM(tr.rating) AS total_ratings FROM therapist_reviews AS tr, (
                                        SELECT * FROM `{$tableTherapist}` AS t
                                        WHERE (t.`name` LIKE '%{$query}%' OR t.`email` LIKE '%{$query}%' OR t.`short_description` LIKE '%{$query}%') AND t.`is_freelancer` = '1'
                                        LIMIT {$limit}
                                    ) AS tp 
                                    WHERE tp.id = tr.therapist_id GROUP BY tr.therapist_id) AS t1 
                               ORDER BY t1.total_ratings DESC;");
        $subQuery = DB::table($tableTherapist)->where(function ($qry) use ($query) {
                                                    $qry->where("name", "LIKE", "%" . $query . "%")
                                                        ->orWhere("email", "LIKE", "%" . $query . "%")
                                                        ->orWhere("short_description", "LIKE", "%" . $query . "%");
                                                    })
                                                ->where("is_freelancer", "=", "1")
                                                ->limit($limit);
        // DB::raw("({$subQuery->toSql()})")
        $getData = DB::table(DB::raw("({$subQuery->toSql()})"))->mergeBindings($subQuery)
                                                    ->select(DB::raw("{$tableTherapist}.*, SUM({$tableTherapistReview}.review) AS total_ratings FROM {$tableTherapistReview}"))
                                                    ->where("id", "=", "therapist_id")->toSql(); */
        /* $subQuery = DB::table($tableTherapist)->where(function ($qry) use ($query) {
                                                    $qry->where("name", "LIKE", "%" . $query . "%")
                                                        ->orWhere("email", "LIKE", "%" . $query . "%")
                                                        ->orWhere("short_description", "LIKE", "%" . $query . "%");
                                                    })
                                                ->where("is_freelancer", "=", "1")
                                                ->limit($limit);
        $subQuery1 = DB::select(DB::raw("SELECT tp.*, SUM(tr.rating) AS total_ratings FROM {$tableTherapistReview} AS tr, ({$subQuery->toSql()}) as tp WHERE tp.id = tr.therapist_id GROUP BY tr.therapist_id"))->toSql(); */
        // dd($getData);

        if (!empty($getData) && !$getData->isEmpty()) {
            /* $therapistIds = $therapistInfos = [];
            $getData->each(function($value, $index) use (&$therapistIds, &$therapistInfos) {
                // $getReview = $this->therapistReviewRepository->getWhere('therapist_id', $value->id);
                $therapistIds[]             = $value->id;
                $therapistInfos[$value->id] = $value;
            });

            $getReviews = $this->therapistReviewRepository->getWhereIn('therapist_id', $therapistIds);

            $reviewCount = [];
            if (!empty($getReviews) && !$getReviews->isEmpty()) {
                $getReviews->each(function($value) use(&$reviewCount) {
                    $reviewCount = $this->reviewCount($value);
                });
            }
            if (!empty($reviewCount)) {
                arsort($reviewCount);
                foreach ($reviewCount as $therapistId => $totalRatings) {
                    $response[] = $therapistInfos[$therapistId];
                }
            } */

            $getData->map(function($value, $key) use($getData) {
                // Check is therapist busy or not.
                /* $bookingInfos = $this->bookingInfo->where('therapist_id', $value->id)->where('is_done', '1')->get();
                if (!empty($bookingInfos) && !$bookingInfos->isEmpty()) {
                    $getData->forget($key);
                } */

                // Check whoes earned less per hour for this day.
                
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
}

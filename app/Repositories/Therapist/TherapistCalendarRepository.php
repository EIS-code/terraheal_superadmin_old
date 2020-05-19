<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\TherapistRepository;
use App\TherapistCalendar;
use Carbon\Carbon;
use DB;

class TherapistCalendarRepository extends BaseRepository
{
    protected $therapist, $therapistCalendar;
    public    $isFreelancer = '0', $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapist         = new TherapistRepository();
        $this->therapistCalendar = new TherapistCalendar();

        $this->errorMsg   = [];
        $this->successMsg = [];
    }

    public function create(array $data)
    {
        $therapistCalendar = [];
        DB::beginTransaction();

        try {
            if (!empty($data['dates'])) {
                $now         = Carbon::now();
                $therapistId = (!empty($data['therapist_id'])) ? (int)$data['therapist_id'] : NULL;

                // Check is therapist exists.
                $getTherapist = $this->therapist->getWhereMany(['id' => $therapistId, 'is_freelancer' => $this->isFreelancer]);
                if (empty($getTherapist) || $getTherapist->isEmpty()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Therapist didn\'t found.'
                    ]);
                }

                foreach ($data['dates'] as &$dates) {
                    $dates['therapist_id'] = $therapistId;
                    $dates['created_at']   = $now;
                    $dates['updated_at']   = $now;
                    $validator = $this->therapistCalendar->validator($dates);
                    if ($validator->fails()) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => $validator->errors()->first()
                        ]);
                    }

                    $getTherapistCalendar = $this->therapistCalendar->where('therapist_id', '=', $dates['therapist_id'])->whereRaw("DATE(`date`) = '" . $dates['date'] . "'")->get();
                    if (!empty($getTherapistCalendar) && !$getTherapistCalendar->isEmpty()) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => "Given date already exists. Please edit it."
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Please provide valid date and times.'
                ]);
            }

            $therapistCalendar = $this->therapistCalendar;
            $therapistCalendar->insert($data['dates']);
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist calender created successfully !'
        ]);
    }

    public function all()
    {
        return $this->therapistCalendar->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapist->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistCalendar->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist calender found successfully !',
                'data' => $data
                ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function updateTime(int $therapistId, string $date)
    {
        DB::beginTransaction();

        try {
            // Check is therapist exists.
            $getTherapist = $this->therapist->getWhereMany(['id' => $therapistId, 'is_freelancer' => $this->isFreelancer]);
            if (empty($getTherapist) || $getTherapist->isEmpty()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Therapist didn\'t found.'
                ]);
            }

            $getTherapistCalendar = $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->get();

            if (empty($getTherapistCalendar) || $getTherapistCalendar->isEmpty()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Therapist calender date doesn\'t found.'
                ]);
            }

            $data['therapist_id'] = $therapistId;
            $validator = $this->therapistCalendar->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist calender updated successfully !'
        ]);
    }

    public function absent(array $data)
    {
        DB::beginTransaction();

        try {
            if (!empty($data)) {
                $therapistId = (!empty($data['therapist_id'])) ? $data['therapist_id'] : 0;
                $dates       = (!empty($data['dates'])) ? $data['dates'] : [];

                if (empty($therapistId)) {
                    $this->errorMsg[] = "Please provide therapist id.";
                }
                if (empty($dates)) {
                    $this->errorMsg[] = "Please provide valid dates.";
                }

                foreach ($dates as $date) {
                    // Check is therapist and date exists.
                    $getTherapistCalendar = $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->get();
                    if (empty($getTherapistCalendar) || $getTherapistCalendar->isEmpty()) {
                        $this->errorMsg[] = "Therapist or present date didn't found.";
                    }
                }

                if ($this->isErrorFree()) {
                    $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->update(['is_absent' => '1']);
                }
            } else {
                $this->errorMsg[] = "Please provide valid dates for absent therapist.";
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        $this->successMsg[] = "Therapist absent successfully !";

        return $this;
    }

    public function delete(int $therapistId, string $date)
    {
        DB::beginTransaction();

        try {
            // Check is therapist exists.
            $getTherapist = $this->therapist->getWhereMany(['id' => $therapistId, 'is_freelancer' => $this->isFreelancer]);
            if (empty($getTherapist) || $getTherapist->isEmpty()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Therapist didn\'t found.'
                ]);
            }

            $getTherapistCalendar = $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->get();

            if (!empty($getTherapistCalendar)) {
                $this->therapistCalendar->where('therapist_id', '=', $therapistId)->whereRaw("DATE(`date`) = '" . $date . "'")->delete();
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Therapist calender not found.'
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist calender deleted successfully !'
        ]);
    }

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $therapistCalendar = $this->therapistCalendar->find($id);

        if (!empty($therapistCalendar)) {
            return $therapistCalendar->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }
}

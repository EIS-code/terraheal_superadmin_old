<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistReviewComment;
use Carbon\Carbon;
use DB;

class TherapistReviewCommentRepository extends BaseRepository
{
    protected $therapistReviewComment;

    public function __construct()
    {
        parent::__construct();
        $this->therapistReviewComment = new TherapistReviewComment();
    }

    public function create(array $data)
    {
        $therapistReviewComment = [];
        DB::beginTransaction();

        try {
            $validator = $this->therapistReviewComment->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $now                = Carbon::now();
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $therapistReviewComment = $this->therapistReviewComment;
            $therapistReviewComment->insert($data);
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist review comment created successfully !',
            'data' => $therapistReviewComment
        ]);
    }

    public function all()
    {
        return $this->therapistReviewComment->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistReviewComment->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistReviewComment->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist review comment found successfully !',
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
        $therapistReviewComment = $this->therapistReviewComment->find($id);

        if (!empty($therapistReviewComment)) {
            return $therapistReviewComment->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

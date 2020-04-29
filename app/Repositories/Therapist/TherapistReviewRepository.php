<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\Repositories\Therapist\TherapistReviewCommentRepository;
use App\TherapistReview;
use Carbon\Carbon;
use DB;

class TherapistReviewRepository extends BaseRepository
{
    protected $therapistReview;

    public function __construct()
    {
        parent::__construct();
        $this->therapistReview            = new TherapistReview();
        $this->therapistReviewCommentRepo = new TherapistReviewCommentRepository();
    }

    public function create(array $data)
    {
        $therapistReview = [];
        DB::beginTransaction();

        try {
            if (!empty($data['rating_info'])) {
                $now = Carbon::now();
                foreach ($data['rating_info'] as $info) {
                    $info['therapist_id'] = $data['therapist_id'];
                    $validator = $this->therapistReview->validator($info);
                    if ($validator->fails()) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => $validator->errors()->first()
                        ]);
                    }
                }
            }

            $commentInfos = [];
            foreach ($data['rating_info'] as &$info) {
                $info['therapist_id'] = $data['therapist_id'];
                $info['created_at']   = $now;
                $info['updated_at']   = $now;
                $therapistReview = $this->therapistReview;
                $therapistReview->insert($info);
                $commentInfos['review_id'][] = $therapistReview->max('id');
            }
            if (!empty($commentInfos) && !empty($data['comment'])) {
                $commentInfos['comment']   = $data['comment'];
                $commentInfos['review_id'] = implode(",", $commentInfos['review_id']);
                $createReviewComment = $this->therapistReviewCommentRepo->create($commentInfos);

                if ($this->getJsonResponseCode($createReviewComment) !== 200) {
                    return $createReviewComment;
                }
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist review created successfully !'
        ]);
    }

    public function all()
    {
        return $this->therapistReview->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistReview->where($column, $value)->get();
    }

    public function getWhereIn(string $column, array $value)
    {
        return $this->therapistReview->whereIn($column, $value)->get();
    }

    public function getWhereInWithGroup(string $column, array $value, string $groupBy = 'therapist_id')
    {
        return $this->therapistReview->whereIn($column, $value)->groupBy($groupBy)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistReview->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist review found successfully !',
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
        $therapistReview = $this->therapistReview->find($id);

        if (!empty($therapistReview)) {
            return $therapistReview->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

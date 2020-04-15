<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Review;
use DB;

class ReviewRepository extends BaseRepository
{
    protected $review;

    public function __construct()
    {
        parent::__construct();
        $this->review = new Review();
    }

    public function create(int $userId, int $rating)
    {
        $review = [];
        DB::beginTransaction();

        try {
            $data      = ['rating' => $rating, 'user_id' => $userId];
            $validator = $this->review->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $review          = $this->review;
            $review->rating  = $rating;
            $review->user_id = $userId;

            $review->fill($data);
            $review->save();
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Review created successfully !',
            'data' => $review
        ]);
    }

    public function all()
    {
        return $this->review->all();
    }

    public function getWhere($column, $value)
    {
        return $this->review->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $reviewData = $this->review->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Review found successfully !',
                'data' => $reviewData
            ]);
        }

        return $reviewData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id, $isApi = false)
    {
        $review = $this->review->find($id);

        if (!empty($review)) {
            $isDeleted = $review->delete();
            if ($isApi === true) {
                if ($isDeleted) {
                    return response()->json([
                        'code' => 200,
                        'msg'  => 'Review deleted successfully !'
                    ]);
                } else {
                    return response()->json([
                        'code' => 401,
                        'msg'  => 'Something went wrong.'
                    ]);
                }
            }
            return $isDeleted;
        }

        if ($isApi === true) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Review not found.'
            ]);
        }

        return false;
    }

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $review = $this->review->find($id);

        if (!empty($review)) {
            return $review->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

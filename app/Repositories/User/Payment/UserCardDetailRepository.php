<?php

namespace App\Repositories\User\Payment;

use App\Repositories\BaseRepository;
use App\UserCardDetail;
use DB;

class UserCardDetailRepository extends BaseRepository
{
    protected $userCardDetail;

    public function __construct()
    {
        parent::__construct();
        $this->userCardDetail = new UserCardDetail();
    }

    public function create(array $data)
    {
        $userCardDetail = [];
        DB::beginTransaction();

        try {
            $validator = $this->userCardDetail->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userCardDetail                 = $this->userCardDetail;
            $userCardDetail->holder_name    = $data['holder_name'];
            $userCardDetail->card_number    = $data['card_number'];
            $userCardDetail->exp_month      = $data['exp_month'];
            $userCardDetail->exp_year       = $data['exp_year'];
            $userCardDetail->cvv            = $data['cvv'];
            $userCardDetail->zip_code       = $data['zip_code'];
            $userCardDetail->user_id        = $data['user_id'];
            $userCardDetail->save();
        } catch(Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User card details saved successfully !',
            'data' => $userCardDetail
        ]);
    }

    public function all()
    {}

    public function getWhere($column, $value)
    {
        return $this->userCardDetail->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $userCardDetailData = $this->userCardDetail->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User card details found successfully !',
                'data' => $userCardDetailData
            ]);
        }

        return $userCardDetailData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userCardDetail = $this->userCardDetail->find($id);

        if (!empty($userCardDetail)) {
            return $userCardDetail->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

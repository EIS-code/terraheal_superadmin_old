<?php

namespace App\Repositories\User\Gift\Voucher;

use App\Repositories\BaseRepository;
use App\UserGiftVoucher;
use App\UserGiftVoucherInfo;
use App\UserGiftVoucherTheme;
use Illuminate\Http\Request;
use DB;

class UserGiftVoucherRepository extends BaseRepository
{
    protected $userGiftVoucher, $userGiftVoucherInfo, $userGiftVoucherTheme;

    public function __construct()
    {
        parent::__construct();
        $this->userGiftVoucher      = new UserGiftVoucher();
        $this->userGiftVoucherInfo  = new UserGiftVoucherInfo();
        $this->userGiftVoucherTheme = new UserGiftVoucherTheme();
    }

    public function create(array $data)
    {
        $userGiftVoucher = [];
        DB::beginTransaction();

        try {
            $uniqueId = mt_rand(10000000,99999999);

            // Check exists.
            $check = $this->getWhereFirst('unique_id', $uniqueId);
            if (!empty($check)) {
                $uniqueId = mt_rand(10000000,99999999);
            }

            $data['unique_id'] = $uniqueId;

            if (!empty($data['preference_email_date'])) {
                $emailDate = $data['preference_email_date'] = date("Y-m-d", $data['preference_email_date']);
            }

            $validator = $this->userGiftVoucher->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userGiftVoucher = $this->userGiftVoucher;

            $userGiftVoucher->fill($data);
            $save = $userGiftVoucher->save();

            if ($save) {
                $today     = strtotime(date('Y-m-d'));
                $emailDate = strtotime($emailDate);

                if ($today == $emailDate) {
                    // Send Email.
                } else {
                    // Set console command for send email for the future date.
                }
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User gift voucher created successfully !',
            'data' => $userGiftVoucher
        ]);
    }

    public function all($isApi = false)
    {
        $data = $this->userGiftVoucher->all();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User gift vouchers found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User gift voucher not found !',
                'data' => []
            ]);
        }

        return$data;
    }

    public function getWhere($column, $value)
    {
        return $this->userGiftVoucher->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userGiftVoucher->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User gift voucher found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function getGiftVouchers(int $userId)
    {
        if (!empty($userId)) {
            $data = $this->getWhere('user_id', $userId);

            if (!empty($data) && !$data->isEmpty()) {
                $data->map(function($value) {
                    $value->start_from = $value->created_at;
                    $value->last_date  = date("Y-m-d", strtotime(date("Y-m-d", strtotime($value->created_at)) . " + ".GIFT_VOUCHER_LIMIT." days"));
                });

                return response()->json([
                    'code' => 200,
                    'msg'  => 'User gift voucher found successfully !',
                    'data' => $data
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User gift voucher not found !',
            'data' => []
        ]);
    }

    public function getGiftVoucherInfos()
    {
        $getInfos = $this->userGiftVoucherInfo->all();

        if (!empty($getInfos) && !$getInfos->isEmpty()) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User gift voucher info found successfully !',
                'data' => $getInfos
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User gift voucher info not found !',
            'data' => []
        ]);
    }

    public function getGiftVoucherDesigns()
    {
        $getDesigns = $this->userGiftVoucherTheme->with('designs')->get();

        if (!empty($getDesigns) && !$getDesigns->isEmpty()) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User gift voucher designs found successfully !',
                'data' => $getDesigns
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User gift voucher design not found !',
            'data' => []
        ]);
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userGiftVoucher = $this->userGiftVoucher->find($id);

        if (!empty($userGiftVoucher)) {
            return $userGiftVoucher->get();
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

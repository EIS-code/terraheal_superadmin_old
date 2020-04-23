<?php

namespace App\Repositories\User\Payment;

use App\Repositories\BaseRepository;
use App\BookingPayment;
use Carbon\Carbon;
use DB;

class BookingPaymentRepository extends BaseRepository
{
    protected $bookingPayment;

    public function __construct()
    {
        parent::__construct();
        $this->bookingPayment = new BookingPayment();
    }

    public function create(array $data)
    {
        $bookingPayment = [];
        DB::beginTransaction();

        try {
            $validator = $this->bookingPayment->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $bookingPayment                         = $this->bookingPayment;
            $bookingPayment->final_amounts          = $data['final_amounts'];
            $bookingPayment->paid_amounts           = $data['paid_amounts'];
            $bookingPayment->remaining_amounts      = $data['remaining_amounts'];
            $bookingPayment->paid_percentage        = $data['paid_percentage'];
            $bookingPayment->is_success             = $data['is_success'];
            $bookingPayment->api_responce           = $data['api_responce'];
            $bookingPayment->currency_id            = $data['currency_id'];
            $bookingPayment->booking_id             = $data['booking_id'];
            $bookingPayment->shop_payment_detail_id = $data['shop_payment_detail_id'];
            $bookingPayment->save();
        } catch (Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Booking payment created successfully !',
            'data' => $bookingPayment
        ]);
    }

    public function all()
    {}

    public function getWhere($column, $value)
    {
        return $this->bookingPayment->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $paymentData = $this->user->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Payment found successfully !',
                'data' => $paymentData
            ]);
        }

        return $paymentData;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $payment = $this->bookingPayment->find($id);

        if (!empty($payment)) {
            return $payment->get();
        }

        return NULL;
    }

    public function errors()
    {}
}

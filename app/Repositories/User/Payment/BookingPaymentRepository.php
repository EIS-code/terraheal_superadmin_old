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

            dd($data);
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

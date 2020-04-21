<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Repositories\User\BookingRepository;

class BookingPayment extends Model
{
    protected $fillable = [
        'final_amounts',
        'paid_amounts',
        'remaining_amounts',
        'paid_percentage',
        'is_success',
        'api_responce',
        'currency_id',
        'booking_id',
        'shop_payment_detail_id'
    ];

    public function validator(array $data)
    {
        // $data = new Rules\CheckValidBookingPayment($data);
        $bookingRepo = new BookingRepository();
        $bookingInfo = $bookingRepo->getWhereFirst('id', $data['booking_id']);
        if (!empty($bookingInfo)) {
            $massagePrice = $bookingInfo->massage_price_id;
            dd($massagePrice);
        }

        $validator = Validator::make($data, [
            'paid_amounts'           => ['required', 'float'],
            'final_amounts'          => ['required', 'float'],
            'paid_amounts'           => ['required', 'float'],
            'remaining_amounts'      => ['required', 'float'],
            'paid_percentage'        => ['required', 'integer'],
            'is_success'             => ['required', 'integer'],
            'api_responce'           => ['max:255'],
            'currency_id'            => ['required', 'integer'],
            'booking_id'             => ['required', 'integer'],
            'shop_payment_detail_id' => ['required', 'integer']
        ]);

        return $validator;
    }
}

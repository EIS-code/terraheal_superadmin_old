<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\MassagePrice;
use App\BookingInfo;
use App\MassagePreferenceOption;
use App\MassagePreferenceOption;

class BookingMassage extends BaseModel
{
    protected $fillable = [
        'price',
        'cost',
        'origional_price',
        'origional_cost',
        'exchange_rate',
        'preference',
        'notes_of_injuries',
        'massage_timing_id',
        'massage_prices_id',
        'booking_info_id',
        'massage_preference_option_id'
    ];

    public function validator(array $data, $excludeBookingInfoId = false)
    {
        $validator = Validator::make($data, [
            /*'price'                              => ['required', 'between:0,99.99'],
            'cost'                               => ['required', 'between:0,99.99'],
            'origional_price'                    => ['required', 'between:0,99.99'],
            'origional_cost'                     => ['required', 'between:0,99.99'],
            'exchange_rate'                      => ['required', 'between:0,99.99'],
            'massage_timing_id'                  => ['required', 'integer', 'exists:' . MassageTiming::getTableName() . ',id'],*/
            '*.massage_info.*.notes_of_injuries' => ['max:255'],
            '*.massage_info.*.massage_prices_id' => ['required', 'integer', 'exists:' . MassagePrice::getTableName() . ',id'],
            'booking_info_id'                    => ($excludeBookingInfoId) ? [] : ['required', 'integer', 'exists:' . BookingInfo::getTableName() . ',id'],
            '*.massage_info.*.gender_preference' => ['required', 'exists:' . MassagePreferenceOption::getTableName() . ',id'],
            '*.massage_info.*.massage_preference_option_id' => ['required', 'integer', 'exists:' . MassagePreferenceOption::getTableName() . ',id']
        ]);

        return $validator;
    }
}

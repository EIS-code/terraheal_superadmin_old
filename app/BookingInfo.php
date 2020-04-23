<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BookingInfo extends Model
{
    protected $fillable = [
        'preference',
        'location',
        'massage_date',
        'massage_time',
        'notes_of_injuries',
        'is_cancelled',
        'cancelled_reason',
        'imc_type',
        'massage_timing_id',
        'therapist_id',
        'massage_price_id',
        'booking_id'
    ];

    const PREFERENCE_MALE   = 'm';
    const PREFERENCE_FEMALE = 'F';
    public static $preferenceTypes = [
        self::PREFERENCE_MALE   => 'Male',
        self::PREFERENCE_FEMALE => 'Female'
    ];

    const IS_CANCELLED_YES  = 1;
    const IS_CANCELLED_NOPE = 0;
    public static $isCancelledTypes = [
        self::IS_CANCELLED_YES  => 'Yes',
        self::IS_CANCELLED_NOPE => 'Nope'
    ];

    const IMC_TYPE_ASAP      = 1;
    const IMC_TYPE_SCHEDULED = 2;
    public static $imcTypes = [
        self::IMC_TYPE_ASAP      => 'ASAP',
        self::IMC_TYPE_SCHEDULED => 'Scheduled'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'preference'        => ['required', 'in:m,f'],
            'location'          => ['required', 'max:255'],
            'massage_date'      => ['required', 'date_format:Y-m-d'],
            'massage_time'      => ['required', 'date_format:Y-m-d H:i:s'],
            'notes_of_injuries' => ['max:255'],
            'is_cancelled'      => ['in:0,1'],
            'cancelled_reason'  => ['mas:255'],
            'imc_type'          => ['required', 'in:1,2'],
            'massage_timing_id' => ['required', 'integer'],
            'therapist_id'      => ['required', 'integer'],
            'massage_price_id'  => ['required', 'integer'],
            'booking_id'        => ['required', 'integer']
        ]);
    }

    public function bookings()
    {
        return $this->belongsTo('App\Booking', 'booking_id', 'id');
    }

    public function massagePrice()
    {
        return $this->belongsTo('App\MassagePrice', 'massage_price_id', 'id');
    }
}

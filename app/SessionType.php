<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class SessionType extends BaseModel
{
    protected $fillable = [
        'type',
        'descriptions',
        'booking_type',
		'is_removed'
    ];

    public static $bookingTypes = ['0', '1'];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'type'   	   => ['required', 'string'],
            'descriptions' => ['nullable', 'string'],
            'booking_type' => ['in:' . implode(",", self::$bookingTypes)]
        ]);
    }
}

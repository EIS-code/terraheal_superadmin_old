<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Booking extends Model
{
    protected $fillable = [
        'booking_type',
        'special_notes',
        'total_persons',
        'copy_with_id',
        'user_id'
    ];

    const BOOKING_TYPE_IMC = 1;
    const BOOKING_TYPE_HHV = 2;

    public static $bookingTypes = [
        self::BOOKING_TYPE_IMC => 'In massage center',
        self::BOOKING_TYPE_HHV => 'Home / Hotel visit'
    ];

    public function validator(array $data, $isUpdate = false)
    {
        $validatorExtended = [];
        if ($isUpdate === false) {
            $totalBookingInfos = (!empty($data['booking_info']) && is_array($data['booking_info']) ? count($data['booking_info']) : 0);
            $validatorExtended = ['total_persons' => ['required', 'integer', new Rules\CheckValidBookingCount($totalBookingInfos)]];
        }

        $validator = Validator::make($data, array_merge([
            'booking_type'  => ['required', 'in:1,2'],
            'special_notes' => ['max:255'],
            'copy_with_id'  => ['max:255'],
            'user_id'       => ['required', 'integer']
        ], $validatorExtended));

        return $validator;
    }

    public function bookingInfo()
    {
        return $this->hasMany('App\BookingInfo', 'booking_id', 'id');
    }
}

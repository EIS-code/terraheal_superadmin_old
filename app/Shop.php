<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class Shop extends BaseModel
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'longitude',
        'latitude',
        'owner_name',
        'tel_number',
        'owner_mobile_number',
        'owner_email',
        'email',
        'time_zone',
        'open_time',
        'close_time',
        'open_day_from',
        'open_day_to',
        'user_name',
        'password',
        'city_id',
        'country_id',
        'currency_id'
    ];

    protected $hidden = ['remember_token', 'created_at', 'updated_at'];

    public $shopDays = [
        '0' => 'Mon',
        '1' => 'Tue',
        '2' => 'Wed',
        '3' => 'Thu',
        '4' => 'Fri',
        '5' => 'Sat',
        '6' => 'Sun'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        $user = NULL;
        if ($isUpdate === true && !empty($id)) {
            $emailValidator      = ['unique:shops,email,' . $id];
            $ownerEmailValidator = ['unique:shops,owner_email,' . $id];
        } else {
            $emailValidator      = ['unique:shops'];
            $ownerEmailValidator = ['unique:shops'];
        }

        return Validator::make($data, [
            'name'                => ['required', 'string', 'max:255'],
            'address'             => ['required', 'string', 'max:255'],
            'description'         => ['nullable'],
            'longitude'           => ['required', 'string'],
            'latitude'            => ['required', 'string'],
            'owner_name'          => ['required', 'string', 'max:255'],
            'tel_number'          => ['required', 'string', 'max:50'],
            'owner_mobile_number' => ['required', 'string', 'max:50'],
            'email'               => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
            'owner_email'         => array_merge(['required', 'string', 'owner_email', 'max:255'], $ownerEmailValidator),
            'time_zone'           => ['required', 'string', 'max:255'],
            'open_time'           => ['required', 'date_format:H:i'],
            'close_time'          => ['required', 'date_format:H:i'],
            'open_day_from'       => ['required', 'in:' . array_keys($this->shopDays)],
            'open_day_to'         => ['required', 'in:' . array_keys($this->shopDays)],
            'user_name'           => ['required', 'string', 'max:255'],
            'password'            => ['required', 'string', 'max:255'],
            'city_id'             => ['required', 'integer'],
            'country_id'          => ['required', 'integer'],
            'currency_id'         => ['required', 'integer']
        ]);
    }

    public function getOpenDayFromAttribute($value)
    {
        return $this->shopDays[$value];
    }

    public function getOpenDayToAttribute($value)
    {
        return $this->shopDays[$value];
    }

    public function massages()
    {
        return $this->hasMany('App\Massage', 'shop_id', 'id');
    }
}

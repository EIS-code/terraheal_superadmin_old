<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class Shop extends BaseModel
{
    protected $fillable = [
        'name',
        'address',
        'address2',
        'description',
        'longitude',
        'latitude',
        'zoom',
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
        'shop_user_name',
        'manager_user_name',
        'shop_password',
        'manager_password',
        'city_id',
        'province_id',
        'country_id',
        'currency_id',
        'pin_code'
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
            'name'                => ['nullable', 'string', 'max:255'],
            'address'             => ['nullable', 'string', 'max:255'],
            'address2'            => ['nullable', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'longitude'           => ['nullable', 'string'],
            'latitude'            => ['nullable', 'string'],
            'zoom'                => ['nullable', 'integer'],
            'owner_name'          => ['nullable', 'string', 'max:255'],
            'tel_number'          => ['nullable', 'string', 'max:50'],
            'owner_mobile_number' => ['nullable', 'string', 'max:50'],
            'email'               => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
            'owner_email'         => array_merge(['nullable', 'string', 'owner_email', 'max:255'], $ownerEmailValidator),
            'time_zone'           => ['nullable', 'string', 'max:255'],
            'open_time'           => ['nullable', 'date_format:H:i'],
            'close_time'          => ['nullable', 'date_format:H:i'],
            'open_day_from'       => ['nullable', 'in:' . implode(",", array_keys($this->shopDays))],
            'open_day_to'         => ['nullable', 'in:' . implode(",", array_keys($this->shopDays))],
            'shop_user_name'      => ['required', 'string', 'max:255'],
            'manager_user_name'   => ['required', 'string', 'max:255'],
            'shop_password'       => ['required', 'string', 'max:255'],
            'manager_password'    => ['required', 'string', 'max:255'],
            'city_id'             => ['nullable'],
            'province_id'         => ['nullable'],
            'country_id'          => ['nullable'],
            'currency_id'         => ['nullable'],
            'pin_code'            => ['nullable', 'string', 'max:255']
        ]);
    }

    public function validatorLocation(array $data)
    {
        return Validator::make($data, [
            'address'     => ['required', 'string', 'max:255'],
            'address2'    => ['nullable', 'string', 'max:255'],
            'longitude'   => ['required', 'string'],
            'latitude'    => ['required', 'string'],
            'zoom'        => ['nullable', 'integer'],
            'city_id'     => ['required', 'integer'],
            'country_id'  => ['required', 'integer'],
            'pin_code'    => ['nullable', 'string', 'max:255']
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

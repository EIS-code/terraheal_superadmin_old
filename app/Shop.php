<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class Shop extends BaseModel
{
    protected $fillable = [
        'name',
        'address',
        'longitude',
        'latitude',
        'owner_name',
        'tel_number',
        'owner_mobile_number',
        'owner_email',
        'email',
        'time_zone',
        'user_name',
        'password',
        'city_id',
        'country_id',
        'currency_id'
    ];

    protected $hidden = ['remember_token', 'created_at', 'updated_at'];

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
            'longitude'           => ['required', 'string'],
            'latitude'            => ['required', 'string'],
            'owner_name'          => ['required', 'string', 'max:255'],
            'tel_number'          => ['required', 'string', 'max:50'],
            'owner_mobile_number' => ['required', 'string', 'max:50'],
            'email'               => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
            'owner_email'         => array_merge(['required', 'string', 'owner_email', 'max:255'], $ownerEmailValidator),
            'time_zone'           => ['required', 'string', 'max:255'],
            'user_name'           => ['required', 'string', 'max:255'],
            'password'            => ['required', 'string', 'max:255'],
            'city_id'             => ['required', 'integer'],
            'country_id'          => ['required', 'integer'],
            'currency_id'         => ['required', 'integer']
        ]);
    }
}

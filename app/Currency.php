<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'exchange_rate',
        'country_id'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'code'          => ['required', 'string', 'max:255'],
            'exchange_rate' => ['required', 'float'],
            'country_id'    => ['required', 'integer']
        ]);
    }
}

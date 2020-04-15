<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Country extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'iso3'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:255'],
            'iso3'       => ['required', 'string', 'max:255']
        ]);
    }
}

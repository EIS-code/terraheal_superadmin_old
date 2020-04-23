<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Province extends Model
{
    protected $fillable = [
        'name',
        'country_id'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer']
        ]);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Room extends Model
{
    protected $fillable = [
        'name',
        'shop_id',
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'    => ['required', 'string', 'max:255'],
            'shop_id' => ['required', 'integer']
        ]);
    }
}

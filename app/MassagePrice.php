<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MassagePrice extends Model
{
    protected $fillable = [
        'massage_id',
        'massage_timing_id',
        'price'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'massage_id'        => ['required', 'integer'],
            'massage_timing_id' => ['required', 'integer'],
            'price'             => ['required']
        ]);
    }
}

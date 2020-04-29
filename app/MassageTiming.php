<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MassageTiming extends Model
{
    protected $fillable = [
        'time',
        'massage_id',
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'time'       => ['required', 'time'],
            'massage_id' => ['required', 'integer']
        ]);
    }

    public function pricing()
    {
        return $this->hasMany('App\MassagePrice', 'massage_timing_id', 'id');
    }
}

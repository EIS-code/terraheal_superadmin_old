<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TherapistDocument extends Model
{
    protected $fillable = [
        'type',
        'file_name',
        'therapist_id'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'type'         => ['required', 'in:1,2,3'],
            'file_name'    => ['required', 'string', 'max:255'],
            'therapist_id' => ['required', 'integer']
        ]);
    }
}

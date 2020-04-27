<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TherapistDocument extends Model
{
    protected $fillable = [
        'title',
        'path',
        'therapist_id'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'title'        => ['required', 'string', 'max:255'],
            'path'         => ['required', 'string', 'max:255'],
            'therapist_id' => ['required', 'integer']
        ]);
    }
}

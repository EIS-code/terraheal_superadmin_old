<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class FreelancerTherapistDocument extends Model
{
    protected $fillable = [
        'title',
        'path',
        'freelancer_therapist_id'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'title'                   => ['required', 'string', 'max:255'],
            'path'                    => ['required', 'string', 'max:255'],
            'freelancer_therapist_id' => ['required', 'integer']
        ]);
    }
}

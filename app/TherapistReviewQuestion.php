<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TherapistReviewQuestion extends Model
{
    protected $fillable = [
        'question',
        'brief_description'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'question'          => ['required', 'string', 'max:255'],
            'brief_description' => ['string', 'max:255']
        ]);
    }
}

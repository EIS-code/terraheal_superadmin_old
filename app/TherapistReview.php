<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class TherapistReview extends BaseModel
{
    protected $fillable = [
        'therapist_id',
        'question_id',
        'rating',
        'message'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'therapist_id' => ['required', 'integer'],
            'question_id'  => ['nullable', 'integer'],
            'rating'       => ['required', 'in:1,2,3,4,5'],
            'message'      => ['nullable']
        ]);
    }
}

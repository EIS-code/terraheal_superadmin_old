<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class TherapistReview extends BaseModel
{
    protected $fillable = [
        'therapist_id',
        'question_id',
        'rating',
        'comment'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        return Validator::make($data, [
            'therapist_id' => ['required', 'integer'],
            'question_id'  => ['required', 'integer'],
            'rating'       => ['required', 'in:1,2,3,4,5'],
            'comment'      => ['max:255']
        ]);
    }
}

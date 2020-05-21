<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class therapistEmailOtp extends BaseModel
{
    protected $fillable = [
        'otp',
        'email',
        'is_send',
        'therapist_id'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        if ($isUpdate === true && !empty($id)) {
            $emailValidator = ['unique:therapist_email_otps,email,' . $id];
        } else {
            $emailValidator = ['unique:therapist_email_otps'];
        }

        return Validator::make($data, [
            'otp'          => ['required', 'max:4'],
            // 'email'        => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
            'email'        => ['required', 'email'],
            'is_send'      => ['required', 'in:0,1'],
            'therapist_id' => ['required', 'integer']
        ]);
    }
}

<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class Therapist extends BaseModel
{
    protected $fillable = [
        'name',
        'dob',
        'gender',
        'email',
        'tel_number',
        'hobbies',
        'short_description',
        'is_freelancer',
        'paid_percentage',
        'is_deleted',
        'shop_id',
        'avatar',
        'avatar_original',
        'device_token',
        'device_type',
        'app_version',
        'oauth_uid',
        'oauth_provider',
        'profile_photo',
        'password',
        'is_email_verified',
        'is_mobile_verified'
    ];

    public $profilePhotoPath = 'therapist\profile\\';

    const GENDER_MALE   = 'm';
    const GENDER_FEMALE = 'f';

    public static $bookingTypes = [
        self::GENDER_MALE   => 'Male',
        self::GENDER_FEMALE => 'Female'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        $user = NULL;
        if ($isUpdate === true && !empty($id)) {
            $emailValidator = ['unique:therapists,email,' . $id];
        } else {
            $emailValidator = ['unique:therapists'];
        }

        return Validator::make($data, [
            'name'              => ['required', 'string', 'max:255'],
            'dob'               => ['date'],
            'gender'            => ['in:m,f'],
            'email'             => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
            'tel_number'        => ['string', 'max:50'],
            'hobbies'           => ['string', 'max:255'],
            'short_description' => ['string', 'max:255'],
            'shop_id'           => ['integer'],
            'is_freelancer'     => ['required', 'in:0,1'],
            'paid_percentage'   => ['integer'],
            'avatar'            => ['max:255'],
            'avatar_original'   => ['max:255'],
            'device_token'      => ['max:255'],
            'device_type'       => ['max:255'],
            'app_version'       => ['max:255'],
            'oauth_uid'         => ['max:255'],
            'oauth_provider'    => [(!empty($data['oauth_uid']) ? 'required' : ''), (!empty($data['oauth_uid']) ? 'in:1,2,3,4' : '')],
            'password'          => [(!$isUpdate ? 'required': ''), 'min:6', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            'is_email_verified' => ['in:0,1'],
            'is_mobile_verified' => ['in:0,1'],
        ], [
            'password.regex'    => 'Password should contains at least one [a-z, A-Z, 0-9, @, $, !, %, *, #, ?, &].'
        ]);
    }

    public function validateProfilePhoto($request)
    {
        return Validator::make($request->all(), [
            'profile_photo' => 'mimes:jpeg,png,jpg',
        ], [
            'profile_photo' => 'Please select proper file. The file must be a file of type: jpeg, png, jpg.'
        ]);
    }

    public function selectedMassages()
    {
        return $this->hasMany('App\TherapistSelectedMassage', 'therapist_id', 'id');
    }

    public function selectedTherapies()
    {
        return $this->hasMany('App\TherapistSelectedTherapy', 'therapist_id', 'id');
    }
}

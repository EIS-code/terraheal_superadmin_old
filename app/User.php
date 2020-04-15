<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dob',
        'country',
        'email',
        'tel_number',
        'nif',
        'address',
        'photo',
        'oauth_uid',
        'oauth_provider',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/

    const OAUTH_PROVIDER_GOOGLE   = 1;
    const OAUTH_PROVIDER_FACEBOOK = 2;
    const OAUTH_PROVIDER_TWITTER  = 3;
    const OAUTH_PROVIDER_LINKEDIN = 4;
    public static $oauthProviders = [
        self::OAUTH_PROVIDER_GOOGLE   => 'Google',
        self::OAUTH_PROVIDER_FACEBOOK => 'Facebook',
        self::OAUTH_PROVIDER_TWITTER  => 'Twitter',
        self::OAUTH_PROVIDER_LINKEDIN => 'LinkedIn'
    ];

    public function validator(array $data, $id = false, $isUpdate = false)
    {
        $user = NULL;
        if ($isUpdate === true && !empty($id)) {
            $emailValidator = ['unique:users,email,' . $id];
        } else {
            $emailValidator = ['unique:users'];
        }

        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => array_merge(['required', 'string', 'email', 'max:255'], $emailValidator),
        ]);
    }
}

<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Massage;
use App\UserGiftVoucherThemeDesign;

class UserGiftVoucher extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recipient_name',
        'recipient_last_name',
        'recipient_second_name',
        'recipient_mobile',
        'recipient_email',
        'giver_first_name',
        'giver_last_name',
        'giver_mobile',
        'giver_email',
        'giver_message_to_recipient',
        'preference_email',
        'user_id',
        'massage_id',
        'design_id',
        'is_removed'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'recipient_name'             => ['required', 'string', 'max:255'],
            'recipient_last_name'        => ['required', 'string', 'max:255'],
            'recipient_second_name'      => ['nullable', 'string', 'max:255'],
            'recipient_mobile'           => ['required', 'string', 'max:255'],
            'recipient_email'            => ['required', 'email', 'max:255'],
            'giver_first_name'           => ['required', 'string', 'max:255'],
            'giver_last_name'            => ['required', 'string', 'max:255'],
            'giver_mobile'               => ['required', 'string'],
            'giver_email'                => ['required', 'email', 'max:255'],
            'giver_message_to_recipient' => ['required', 'string'],
            'preference_email'           => ['required', 'email', 'max:255'],
            'user_id'                    => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'massage_id'                 => ['required', 'integer', 'exists:' . Massage::getTableName() . ',id'],
            'design_id'                  => ['required', 'integer', 'exists:' . UserGiftVoucherThemeDesign::getTableName() . ',id'],
            'is_removed'                 => ['integer', 'in:0,1']
        ]);
    }
}

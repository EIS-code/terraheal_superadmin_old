<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\MassagePreferenceOption;
use App\User;

class SelectedMassagePreference extends BaseModel
{
    protected $fillable = [
        'value',
        'is_removed',
        'mp_option_id',
        'user_id'
    ];

    public $radioOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'value'        => ['required', 'string'],
            'is_removed'   => ['integer', 'in:0,1'],
            'mp_option_id' => ['required', 'exists:' . MassagePreferenceOption::getTableName() . ',id'],
            'user_id'      => ['required', 'exists:' . User::getTableName() . ',id']
        ]);
    }
}

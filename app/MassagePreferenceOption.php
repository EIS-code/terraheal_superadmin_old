<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\MassagePreference;

class MassagePreferenceOption extends BaseModel
{
    protected $fillable = [
        'name',
        'is_removed',
        'massage_preference_id',
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'                  => ['required', 'integer'],
            'is_removed'            => ['integer', 'in:0,1'],
            'massage_preference_id' => ['required', 'exists:' . MassagePreference::getTableName() . ',id']
        ]);
    }

    public function selectedPreferences()
    {
        return $this->hasOne('App\SelectedMassagePreference', 'mp_option_id', 'id')->where('is_removed', '=', self::$notRemoved);
    }
}
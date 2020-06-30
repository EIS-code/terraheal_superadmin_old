<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\MassagePreferenceOption;

class SelectedMassagePreference extends BaseModel
{
    protected $fillable = [
        'value',
        'is_removed',
		'mp_option_id',
    ];

	public function validator(array $data)
    {
        return Validator::make($data, [
            'value'        => ['required', 'integer'],
            'is_removed'   => ['integer', 'in:0,1'],
			'mp_option_id' => ['required', 'exists:' . MassagePreferenceOption::getTableName() . ',id']
        ]);
    }
}

<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class SessionType extends BaseModel
{
    protected $fillable = [
        'type',
        'descriptions',
		'is_removed'
    ];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'type'   	   => ['required', 'string'],
            'descriptions' => ['nullable', 'string'],
        ]);
    }
}

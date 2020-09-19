<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\MassagePreference;
use Illuminate\Http\Request;

class MassagePreferenceOption extends BaseModel
{
    protected $fillable = [
        'name',
        'is_removed',
        'massage_preference_id',
    ];

    public static $massagePressures = [
        1 => [
            1, 2, 3, 4
        ]
    ];

    public static $massageGenders = [
        2 => [
            5, 6, 7, 8, 9
        ]
    ];

    public static $massageFocucAreas = [
        8 => [
            15, 16, 17, 18, 19, 20
        ]
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
        $request = Request();
        $userId  = $request->get('user_id', false);

        $query   = $this->hasOne('App\SelectedMassagePreference', 'mp_option_id', 'id')->where('is_removed', '=', self::$notRemoved)->where('user_id', '=', $userId);

        return $query;
    }
}

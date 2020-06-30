<?php

namespace App\Repositories\Massage\Preference;

use App\Repositories\BaseRepository;
use App\MassagePreference;
use DB;

class MassagePreferenceRepository extends BaseRepository
{
    protected $massagePreference;

    public function __construct()
    {
        parent::__construct();
        $this->massagePreference = new MassagePreference();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->massagePreference->all();
    }

    public function getWhere($column, $value)
    {
        return $this->massagePreference->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->massagePreference->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage preference found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(array $data, int $limit = 10)
    {
        $limit = (!is_numeric($limit)) ? 10 : $limit;

        $getMassagePreferences = $this->massagePreference->with(['preferenceOptions' => function($qry) {
            $qry->with('selectedPreferences');
        }])->where('is_removed', '=', $this->massagePreference::$notRemoved)->limit($limit)->get();

        if (!empty($getMassagePreferences) && !$getMassagePreferences->isEmpty()) {
            $getMassagePreferences->map(function($preferences) {
                if (!empty($preferences->preferenceOptions) && !$preferences->preferenceOptions->isEmpty()) {
                    $preferences->preferenceOptions->map(function($options) {
                        $options->selected = false;
                        $options->value    = NULL;
                        if (!empty($options->selectedPreferences)) {
                            $options->selected = true;
                            $options->value    = $options->selectedPreferences->value;
                        }

                        unset($options->selectedPreferences);
                    });
                }
            });

            return response()->json([
                'code' => 200,
                'msg'  => 'Massage preferences found successfully !',
                'data' => $getMassagePreferences
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage preferences not found !',
            'data' => []
        ]);
    }

    public function errors()
    {}
}

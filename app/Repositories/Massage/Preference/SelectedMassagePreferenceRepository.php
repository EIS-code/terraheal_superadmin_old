<?php

namespace App\Repositories\Massage\Preference;

use App\Repositories\BaseRepository;
use App\SelectedMassagePreference;
use App\MassagePreferenceOption;
use Carbon\Carbon;
use DB;

class SelectedMassagePreferenceRepository extends BaseRepository
{
    protected $selectedMassagePreference, $massagePreferenceOptions;

    public function __construct()
    {
        parent::__construct();
        $this->selectedMassagePreference = new SelectedMassagePreference();
        $this->massagePreferenceOptions  = new MassagePreferenceOption();
    }

    public function create(array $data)
    {
        $dataTemp  = $data;
        $selectedMassagePreference = [];
        DB::beginTransaction();

        try {
            $userId = (!empty($data['user_id'])) ? (int)$data['user_id'] : false;
            $data   = (!empty($data['data'])) ? (array)$data['data'] : [];
            $data   = (!isMultidimentional($data)) ? [$data] : $data;
            $now    = Carbon::now();

            if (!$userId) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Please provide valid user_id.'
                ]);
            }

            if (empty($data)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Data should not be empty and it should be valid.'
                ]);
            }

            $insertData = $matchIds = [];
            foreach ($data as $index => $selected) {
                if (!empty($selected['id'])) {
                    $optionId = (int)$selected['id'];
                    // Get selected option value for first two options.
                    $value = (!empty($selected['value'])) ? (string)$selected['value'] : NULL;
                    if (in_array($optionId, $this->selectedMassagePreference->radioOptions)) {
                        $getOption = $this->massagePreferenceOptions->where('id', '=', $optionId)->first();
                        $value     = (!empty($getOption)) ? $getOption->name : NULL;
                    }

                    $matchIds[$index] = [
                        'mp_option_id' => $optionId,
                        'user_id'      => $userId
                    ];

                    $insertData[$index] = [
                        'value'        => $value,
                        'mp_option_id' => $optionId,
                        'user_id'      => $userId,
                        'created_at'   => $now,
                        'updated_at'   => $now
                    ];

                    $validator = $this->selectedMassagePreference->validator($insertData[$index]);
                    if ($validator->fails()) {
                        return response()->json([
                            'code' => 401,
                            'msg'  => $validator->errors()->first()
                        ]);
                    }

                    $selectedMassagePreference = $this->selectedMassagePreference->updateOrCreate($matchIds[$index], $insertData[$index]);
                }
            }

            /*if (!empty($insertData)) {
                // $selectedMassagePreference = $this->selectedMassagePreference->updateOrCreate($matchIds, $insertData);
            }*/
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage preference created successfully !',
            'request_body' => $dataTemp
        ]);
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function errors()
    {}
}

<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class LocationController extends BaseController
{
    protected $country, $province, $city;

    private $jsonLocation, $countryJsonLocation, $provinceJsonLocation, $cityJsonLocation;

    public function __construct()
    {
        parent::__construct();
        $this->country  = $this->countryRepo;
        $this->province = $this->provinceRepo;
        $this->city     = $this->cityRepo;

        $this->jsonLocation         = storage_path('app\db\json');
        $this->countryJsonLocation  = $this->jsonLocation."\\country.json";
        $this->provinceJsonLocation = $this->jsonLocation."\\province.json";
        $this->cityJsonLocation     = $this->jsonLocation."\\cities.json";
    }

    public function loadCountries()
    {
        $getData = $this->getStorageFiles($this->countryJsonLocation);
        $now     = Carbon::now();
        $create  = false;

        if (!empty($getData)) {
            $getArray   = jsonDecode($getData, true);
            $insertData = [];

            if (!empty($getArray['countries'])) {
                foreach ($getArray['countries'] as $data) {
                    if (empty($data['id']) || empty($data['name'])) {
                        continue;
                    }

                    $insertData[] = ['id' => $data['id'], 'name' => $data['name'], 'short_name' => (!empty($data['sortname']) ? $data['sortname'] : ''), 'created_at' => $now, 'updated_at' => $now];
                }
                if (!empty($insertData)) {
                    $create = $this->country->createMultiple($insertData);
                }
            }
        }

        return $create;
    }

    public function loadProvince()
    {
        $getData = $this->getStorageFiles($this->provinceJsonLocation);
        $now     = Carbon::now();
        $create  = false;

        if (!empty($getData)) {
            $getArray   = jsonDecode($getData, true);
            $insertData = [];

            if (!empty($getArray['province'])) {
                foreach ($getArray['province'] as $data) {
                    if (empty($data['id']) || empty($data['name']) || empty($data['country_id'])) {
                        continue;
                    }

                    $insertData[] = $temp = ['id' => $data['id'], 'name' => $data['name'], 'country_id' => $data['country_id'], 'created_at' => $now, 'updated_at' => $now];
                }
                if (!empty($insertData)) {
                    $create = $this->province->createMultiple($insertData);
                }
            }
        }

        return $create;
    }

    public function loadCities()
    {
        ini_set('max_execution_time', 600);
        $getData = $this->getStorageFiles($this->cityJsonLocation);
        $now     = Carbon::now();
        $create  = false;

        if (!empty($getData)) {
            $getArray   = jsonDecode($getData, true);
            $insertData = [];

            if (!empty($getArray['cities'])) {
                foreach ($getArray['cities'] as $data) {
                    if (empty($data['id']) || empty($data['name']) || empty($data['province_id'])) {
                        continue;
                    }

                    $insertData[] = $temp = ['id' => $data['id'], 'name' => $data['name'], 'province_id' => $data['province_id'], 'created_at' => $now, 'updated_at' => $now];
                }
                if (!empty($insertData)) {
                    $create = $this->city->createMultiple($insertData);
                }
            }
        }

        return $create;
    }

    public function getCountry()
    {
        return $this->country->all(true);
    }

    public function getCity()
    {
        return $this->city->getCity($this->httpRequest->all());
    }

    public function getProvince()
    {
        return $this->province->all(true);
    }
}

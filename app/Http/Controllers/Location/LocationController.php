<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\BaseController;

class LocationController extends BaseController
{
    protected $country, $city;

    public function __construct()
    {
        parent::__construct();
        $this->country = $this->countryRepo;
        $this->city    = $this->cityRepo;
    }

    public function getCountry()
    {
        return $this->country->all();
    }

    public function getCity()
    {
        return $this->city->all();
    }
}

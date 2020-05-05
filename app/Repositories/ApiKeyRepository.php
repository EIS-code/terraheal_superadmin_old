<?php

namespace App\Repositories;

use App\ApiKey;
use DB;

class ApiKeyRepository extends BaseRepository
{
    public $apiKey;

    public function __construct()
    {
        $this->apiKey = new ApiKey();
    }

    public function validate(string $key)
    {
        $getKeyInfo = $this->getWhere('key', $key);

        return (!empty($getKeyInfo) && !$getKeyInfo->isEmpty());
    }

    public function getWhere($column, $value)
    {
        return $this->apiKey->where($column, $value)->get();
    }
}

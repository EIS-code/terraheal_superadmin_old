<?php

namespace App\Http\Controllers\User\Setting;

use App\Http\Controllers\BaseController;

class UserSettingController extends BaseController
{
    protected $userSetting, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->userSetting = $this->userSettingRepo;
        $this->getRequest  = $this->httpRequest->all();
    }

    public function get()
    {
        $userId = $this->httpRequest->get('user_id', false);
        return $this->userSetting->getGlobalResponse($userId, true);
    }

    public function save()
    {
        return $this->userSetting->save($this->getRequest);
    }

    public function updatePassword()
    {
        return $this->userSetting->updatePassword($this->getRequest);
    }

    public function logout()
    {
        return $this->userSetting->logout($this->getRequest);
    }
}

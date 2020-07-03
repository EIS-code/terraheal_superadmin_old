<?php

namespace App\Http\Controllers\User\People;

use App\Http\Controllers\BaseController;

class UserPeopleController extends BaseController
{
    protected $userPeople, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->userPeople = $this->userPeopleRepo;
        $this->getRequest = $this->httpRequest->all();
    }

    public function get()
    {
        $id = $this->httpRequest->get('user_id', false);
        return $this->userPeople->getByUserId($id, true);
    }

    public function create()
    {
        return $this->userPeople->create($this->httpRequest);
    }

    public function update()
    {
        $id = $this->httpRequest->get('id', false);
        return $this->userPeople->update($id, $this->httpRequest);
    }

    public function remove()
    {
        $id = $this->httpRequest->get('id', false);
        return $this->userPeople->remove($id);
    }
}

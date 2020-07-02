<?php

namespace App\Http\Controllers\User\Address;

use App\Http\Controllers\BaseController;

class UserAddressController extends BaseController
{
    protected $userAddress, $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->userAddress = $this->userAddressRepo;
        $this->getRequest  = $this->httpRequest->all();
    }

    public function get()
    {
        $id = $this->httpRequest->get('user_id', false);
        return $this->userAddress->get($id, true);
    }

    public function create()
    {
        return $this->userAddress->create($this->getRequest);
    }

    public function update()
    {
        return $this->userAddress->update($this->getRequest);
    }

    public function remove()
    {
        $id = $this->httpRequest->get('id', false);
        return $this->userAddress->remove($id);
    }
}

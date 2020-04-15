<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;
    protected $userRepo;

    public function __construct()
    {
        $this->httpRequest = Request();
        $this->userRepo    = new UserRepository();
    }
}

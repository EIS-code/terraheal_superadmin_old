<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class ClientController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $request = $this->httpRequest;
        $users   = $this->userRepo->filter($request->all());

        return view('superadmin.clients', compact('users', 'request'));
    }

    public function create()
    {
        
    }
}

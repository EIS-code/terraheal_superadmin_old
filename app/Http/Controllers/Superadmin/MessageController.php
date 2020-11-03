<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class MessageController extends BaseController
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
        return view('superadmin.messages');
    }

    public function create()
    {
        
    }
}

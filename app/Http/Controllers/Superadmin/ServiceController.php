<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class ServiceController extends BaseController
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
        $request  = $this->httpRequest;
        $massages = $this->massageRepo->filter($request->all());

        return view('superadmin.services', compact('massages', 'request'));
    }

    public function create()
    {
        
    }
}

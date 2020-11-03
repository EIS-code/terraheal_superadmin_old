<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class TherapistController extends BaseController
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
        $request    = $this->httpRequest;
        $therapists = $this->therapistRepo->filter($request->all());

        return view('superadmin.therapists', compact('therapists', 'request'));
    }

    public function create()
    {
        
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use App\Massage;
use App\Shop;
use App\Therapist;
use DB;

class SuperadminController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now  = Carbon::now();

        $totalMassages   = Massage::count();
        $totalShops      = Shop::count();
        $totalTherapists = Therapist::count();

        return view('superadmin.dashboard', compact('now', 'totalMassages', 'totalShops', 'totalTherapists'));
    }
}

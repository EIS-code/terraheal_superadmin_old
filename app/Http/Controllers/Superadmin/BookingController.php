<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class BookingController extends BaseController
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
        $request          = $this->httpRequest;
        $bookingsPast     = $this->bookingRepo->getWherePastFuture(null, true, false, false, true)->getData();
        $bookingsUpcoming = $this->bookingRepo->getWherePastFuture(null, false, true, false, true)->getData();
        $bookingsPending  = $this->bookingRepo->getWherePastFuture(null, false, false, true, true)->getData();

        if (!empty($bookingsPast->data)) {
            $bookingsPast = $bookingsPast->data;
        } else {
            $bookingsPast = [];
        }
        if (!empty($bookingsUpcoming->data)) {
            $bookingsUpcoming = $bookingsUpcoming->data;
        } else {
            $bookingsUpcoming = [];
        }
        if (!empty($bookingsPending->data)) {
            $bookingsPending = $bookingsPending->data;
        } else {
            $bookingsPending = [];
        }

        return view('superadmin.bookings', compact('request', 'bookingsPast', 'bookingsUpcoming', 'bookingsPending'));
    }

    public function create()
    {
        
    }
}

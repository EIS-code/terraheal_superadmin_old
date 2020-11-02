<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;

class CenterController extends BaseController
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
        $shops   = $this->shopRepo->filter($request->all());

        if ($request->ajax()) {
            $view = view("superadmin.ajax.list-centers", compact('shops', 'request'))->render();

            // echo response()->json(['list' => $view]);
            echo $view;
        } else {
            $massages = $this->shopRepo->getMassages();

            return view('superadmin.centers', compact('shops', 'request', 'massages'));
        }
    }

    public function create()
    {
        $countries = $this->countryRepo->all();

        return view('superadmin.create', compact('countries'));
    }

    public function store()
    {

    }
}

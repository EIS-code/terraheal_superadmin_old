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
        $request = $this->httpRequest;

        if (empty($request->get('id'))) {
            $request->session()->forget('shopId');
        } else {
            $request->session()->put('shopId', $request->get('id'));
        }

        $countries = $this->countryRepo->all();

        return view('superadmin.create', compact('countries'));
    }

    public function store()
    {
        $request = $this->httpRequest;

        $create = $this->shopRepo->create($request->all())->getData();

        return $this->redirectResponse('superadmin/centers/create', $create);
    }

    public function locationCreate()
    {
        $request = $this->httpRequest;

        $create = $this->shopRepo->createLocation($request->all())->getData();

        if ($create->code == $this->errorCode) {
            return redirect('superadmin/centers/create')->with('error', $create->msg);
        }

        return redirect('superadmin/centers/create')->with('success', $create->msg);
    }

    public function companyCreate()
    {
        $request = $this->httpRequest;

        $create = $this->shopRepo->companyCreate($request->all())->getData();

        return $this->redirectResponse('superadmin/centers/create', $create);
    }

    public function ownerCreate()
    {
        $request = $this->httpRequest;

        $create = $this->shopRepo->ownerCreate($request->all())->getData();

        return $this->redirectResponse('superadmin/centers/create', $create);
    }

    public function documentCreate()
    {
        $request = $this->httpRequest;

        $create = $this->shopRepo->documentCreate($request->all())->getData();

        return $this->redirectResponse('superadmin/centers/create', $create);
    }

    public function getInfo(int $shopId)
    {
        $getInfo = $this->shopRepo->getInfo($shopId)->getData();

        $data = !empty($getInfo->data) ? $getInfo->data : [];

        return view('superadmin.center-edit', compact('data'));
    }

    public function redirectResponse($url, $jsonObject)
    {
        if ($jsonObject->code == $this->errorCode) {
            if (!empty($this->httpRequest->session()->get('shopId'))) {
                return redirect($url . '?id=' . $this->httpRequest->session()->get('shopId'))->with('error', $jsonObject->msg)->withInput();
            } else {
                return redirect($url)->with('error', $jsonObject->msg)->withInput();
            }
        }

        if (!empty($jsonObject->data->id)) {
            return redirect($url . '?id=' . $jsonObject->data->id)->with('success', $jsonObject->msg);
        } else {
            return redirect($url)->with('error', $this->defaultMessage)->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use App\Massage;
use App\MassagePrice;
use App\Shop;
use App\Therapist;
use App\User;
use App\Booking;
use App\BookingInfo;
use App\BookingMassage;
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
        $totalUsers      = User::count();
        $totalBookings   = Booking::where('booking_type', Booking::BOOKING_TYPE_HHV)
                                  ->join(BookingInfo::getTableName(), Booking::getTableName() . '.id', '=', BookingInfo::getTableName() . '.booking_id')
                                  ->where(BookingInfo::getTableName() . '.is_cancelled', BookingInfo::IS_NOT_CANCELLED)
                                  ->count();
        $totalSales      = BookingMassage::join(BookingInfo::getTableName(), BookingMassage::getTableName() . '.booking_info_id', '=', BookingInfo::getTableName() . '.id')
                                         ->where(BookingInfo::getTableName() . '.is_cancelled', BookingInfo::IS_NOT_CANCELLED)
                                         ->sum(BookingMassage::getTableName() . ".price");
        $topItems        = $this->getTopItems();

        return view('superadmin.dashboard', compact('now', 'totalMassages', 'totalShops', 'totalTherapists', 'totalUsers', 'totalBookings', 'totalSales', 'topItems'));
    }

    public function getTopItems()
    {
        $getItems = Massage::select(Massage::getTableName() . ".*")
                           ->leftJoin(MassagePrice::getTableName(), Massage::getTableName() . '.id', '=', MassagePrice::getTableName() . '.massage_id')
                           ->leftJoin(BookingMassage::getTableName(), MassagePrice::getTableName() . '.id', '=', BookingMassage::getTableName() . '.massage_prices_id')
                           ->whereNotNull(BookingMassage::getTableName() . '.id')
                           ->groupBy(Massage::getTableName() . '.id')
                           ->orderBy(DB::RAW('SUM('.BookingMassage::getTableName().'.price)'), 'DESC')
                           ->get();

        if (!empty($getItems) && !$getItems->isEmpty()) {
            return $getItems;
        }

        return [];
    }
}

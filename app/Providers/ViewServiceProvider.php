<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*view()->composer('*', function ($view)
        {
            $view->with('totalUsers', 10);
        });*/
    }

    public function getTopItems()
    {
        $getItems = \App\Massage::select(\App\Massage::getTableName() . ".*")
                                ->leftJoin(\App\MassagePrice::getTableName(), \App\Massage::getTableName() . '.id', '=', \App\MassagePrice::getTableName() . '.massage_id')
                                ->leftJoin(\App\BookingMassage::getTableName(), \App\MassagePrice::getTableName() . '.id', '=', \App\BookingMassage::getTableName() . '.massage_prices_id')
                                ->whereNotNull(\App\BookingMassage::getTableName() . '.id')
                                ->groupBy(\App\Massage::getTableName() . '.id')
                                ->orderBy(DB::RAW('SUM('.\App\BookingMassage::getTableName().'.price)'), 'DESC')
                                ->get();

        if (!empty($getItems) && !$getItems->isEmpty()) {
            return $getItems;
        }

        return [];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $totalUsers    = \App\User::count();
        $totalBookings = \App\Booking::where('booking_type', \App\Booking::BOOKING_TYPE_HHV)
                                     ->join(\App\BookingInfo::getTableName(), \App\Booking::getTableName() . '.id', '=', \App\BookingInfo::getTableName() . '.booking_id')
                                     ->where(\App\BookingInfo::getTableName() . '.is_cancelled', \App\BookingInfo::IS_NOT_CANCELLED)
                                     ->count();
        $totalSales    = \App\BookingMassage::join(\App\BookingInfo::getTableName(), \App\BookingMassage::getTableName() . '.booking_info_id', '=', \App\BookingInfo::getTableName() . '.id')
                                            ->where(\App\BookingInfo::getTableName() . '.is_cancelled', \App\BookingInfo::IS_NOT_CANCELLED)
                                            ->sum(\App\BookingMassage::getTableName() . ".price");
        $topItems      = $this->getTopItems();

        view()->share(compact('totalUsers', 'totalBookings', 'totalSales', 'topItems'));
    }
}

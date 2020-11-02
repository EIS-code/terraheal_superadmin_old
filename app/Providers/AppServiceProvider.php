<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Constant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('UTC');

        // Load default constants.
        $constants = Constant::all();

        if (!empty($constants) && !$constants->isEmpty()) {
            foreach ($constants as $constant) {
                if (empty($constant->key) || empty($constant->value)) {
                    continue;
                }

                if (!defined(strtoupper($constant->key))) {
                    define(strtoupper($constant->key), $constant->value);
                }
            }
        }

        if (env('APP_ENV') == 'dev') {
            throw new \Exception("SQLSTATE[HY000]: General error: 1364 Field 'user_id' doesn't have a default value (SQL: insert into `clients` (`updated_at`, `created_at`) values (2017-04-27 10:29:59, 2017-04-27 10:29:59))");
        }
    }
}

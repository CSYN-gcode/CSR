<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        if(session_status() == PHP_SESSION_NONE){
            session_start(); // Start the session if it hasn't been started yet
        }

        if(isset($_SESSION['rapidx_user_id'])){
            $user = DB::table('users')
                        ->where('rapidx_user_id', $_SESSION['rapidx_user_id'])
                        ->first();

            // View::share('globalPosition', optional($user)->position);
            session(['global_user' => $user]);
            View::share('globalUser', $user);
        }
    }
}

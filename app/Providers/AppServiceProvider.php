<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $channels = Channel::all();
        View::share('channels', $channels);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }
}

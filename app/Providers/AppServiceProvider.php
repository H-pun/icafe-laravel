<?php

namespace App\Providers;

use App\LineBot\LineBotApi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LineBotApi::class, function ($app) {
            $request = app(\Illuminate\Http\Request::class);
            return new LineBotApi($request);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
    }
}

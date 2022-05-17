<?php

namespace App\Providers;

use App\Services\Payment\Iyzico\IyzicoService;
use App\Services\Radius\RadiusService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( config('payment.service') == 'iyzico' ){
            $this->app->bind(IyzicoService::class, function ($app) {
                return new IyzicoService(
                    config('payment.iyzico.api_key'),
                    config('payment.iyzico.api_secret'),
                    config('payment.iyzico.base_url')
                );
            });
        }
        if( config('radius.database.db_host') ){
            $this->app->bind(RadiusService::class, function ($app) {
                return new RadiusService(
                    env('RADIUS_DB_CONNECTION','radiusdb')
                );
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}

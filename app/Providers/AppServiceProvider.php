<?php

namespace App\Providers;

use App\Services\Commons\SMSMessageFormatter;
use App\Services\FreeMoPayService;
use App\Services\messages\ApiMessageService;
use App\Services\messages\NexahService;
use App\Services\messages\TwilioService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->singleton(FreeMoPayService::class, function ($app) {
            return new FreeMoPayService();
        });

        $this->app->singleton(TwilioService::class, function () {
            return new TwilioService();
        });

        $this->app->singleton(NexahService::class, function () {
            return new NexahService();
        });

        $this->app->singleton(ApiMessageService::class, function ($app) {
            return new ApiMessageService($app->make(TwilioService::class), $app->make(NexahService::class));
        });

        $this->app->singleton(SMSMessageFormatter::class, function () {
            return new SMSMessageFormatter();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('fr_CM');
        Paginator::useBootstrap();
    }
}

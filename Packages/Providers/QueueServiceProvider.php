<?php

namespace App\Packages\Providers;

use App\Packages\Queue\QueueService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(QueueService::class, function (Application $app) {
            return new QueueService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

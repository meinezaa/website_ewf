<?php

namespace App\Providers;

use App\Auth\FileUserStore;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileUserStore::class, function ($app): FileUserStore {
            return new FileUserStore($app['config']['auth.legacy_users_file']);
        });
    }
}

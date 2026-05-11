<?php

namespace App\Providers;

use App\Repositories\PermissionRepository;
use App\Services\PermissionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermissionRepository::class);

        $this->app->singleton(PermissionService::class, fn($app) => new PermissionService(
            $app->make(PermissionRepository::class),
        ));
    }

    public function boot(): void {}
}

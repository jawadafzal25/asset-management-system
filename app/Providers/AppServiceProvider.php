<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Observers\AssetObserver;
use App\Observers\CategoryObserver;
use App\Observers\DepartmentObserver;
use App\Observers\EmployeeObserver;
use App\Observers\UserObserver;
use App\Repositories\PermissionRepository;
use App\Services\PermissionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermissionRepository::class);

        $this->app->singleton(PermissionService::class, fn($app) => new PermissionService(
            $app->make(PermissionRepository::class),
        ));
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Register Model Observers
        |--------------------------------------------------------------------------
        |
        | Each observer automatically logs created / updated / deleted events
        | for its model. No changes needed in controllers or services.
        |
        */

        Employee::observe(EmployeeObserver::class);
        Asset::observe(AssetObserver::class);
        Department::observe(DepartmentObserver::class);
        Category::observe(CategoryObserver::class);
        User::observe(UserObserver::class);
    }
}

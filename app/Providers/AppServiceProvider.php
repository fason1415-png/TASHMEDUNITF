<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Doctor;
use App\Observers\DepartmentObserver;
use App\Observers\DoctorObserver;
use App\Support\TenantContext;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(TenantContext::class, fn (): TenantContext => new TenantContext());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Doctor::observe(DoctorObserver::class);
        Department::observe(DepartmentObserver::class);

        RateLimiter::for('survey-submissions', function (Request $request): Limit {
            return Limit::perMinute(15)->by($request->ip() ?: 'anonymous');
        });

        RateLimiter::for('api-survey-submissions', function (Request $request): Limit {
            return Limit::perMinute(30)->by($request->ip() ?: 'anonymous');
        });
    }
}

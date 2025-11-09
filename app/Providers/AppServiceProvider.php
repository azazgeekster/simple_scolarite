<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for data integrity
        \App\Models\ModuleGrade::observe(\App\Observers\ModuleGradeObserver::class);
        \App\Models\StudentModuleEnrollment::observe(\App\Observers\StudentModuleEnrollmentObserver::class);
    }
}

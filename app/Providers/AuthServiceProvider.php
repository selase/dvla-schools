<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Student::class => StudentPolicy::class,
        // Enrollment::class => EnrollmentPolicy::class,
        // Slot::class => SlotPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register any authentication / authorization services.
        // $this->registerPolicies();

        // Optional: global gates for superadmin
        Gate::before(function ($user, $ability) {
            // If you tag superadmins via role:
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}

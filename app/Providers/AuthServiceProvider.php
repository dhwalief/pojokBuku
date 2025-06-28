<?php

namespace App\Providers;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function ($user) {
            return $user->role === UserRole::Admin;
        });
        Gate::define('user-only', function ($user) {
            return $user->role === UserRole::User;
        });
    }
}

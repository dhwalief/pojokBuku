<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
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

        // Mencegah duplikasi definisi
        Gate::define('admin', function ($user) {
            return $user->role === UserRole::Admin;
        });
        Gate::define('user', function ($user) {
            return $user->role === UserRole::User;
        });
    }
    protected $policies = [
        'App\Models\Book' => 'App\Policies\BookPolicy',
        'App\Models\Borrow' => 'App\Policies\BorrowPolicy',
    ];
}

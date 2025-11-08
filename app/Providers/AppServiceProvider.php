<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        $roles = [
            'Super Admin',
            'Admin Bahan Baku',
            'Admin Produksi',
            'Admin Kontrol Kualitas',
            'Admin Keamanan Pangan',
            'Admin Ekspor',
            'User'
        ];

        foreach ($roles as $role) {
            // Gate::define($role, function ($user) use ($role) {
            //     return $user->role->name === $role;
            // });

            Gate::define($role, function ($user) use ($role) {
                if ($user->role->name === 'Super Admin') return true;
                return $user->role->name === $role;
            });

        }

        Gate::define('view-dashboard', function ($user) {
            return $user->role->name !== 'User';
        });
    }
}

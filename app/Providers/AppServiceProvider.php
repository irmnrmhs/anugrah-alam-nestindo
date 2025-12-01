<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;
use App\Models\WBHouse;
use App\Models\Arrival;
use App\Models\Supplier;
use App\Models\Dcertificate;

use App\Observers\DCertificateObserver;
use App\Observers\ShapeObserver;
use App\Observers\FeatherObserver;
use App\Observers\ColorObserver;
use App\Observers\WBHouseObserver;
use App\Observers\ArrivalObserver;
use App\Observers\SupplierObserver;

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

        Shape::observe(ShapeObserver::class);
        Feather::observe(FeatherObserver::class);
        Color::observe(ColorObserver::class);
        WBHouse::observe(WBHouseObserver::class);
        Arrival::observe(ArrivalObserver::class);
        Supplier::observe(SupplierObserver::class);
        Dcertificate::observe(DCertificateObserver::class);
    }
}

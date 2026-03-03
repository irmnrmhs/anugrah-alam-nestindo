<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;
use App\Models\WBHouse;
use App\Models\Arrival;
use App\Models\Supplier;
use App\Models\Dcertificate;
use App\Models\Grade;
use App\Models\GradeColor;
use App\Models\Edge;
use App\Models\Wash;
use App\Models\Correction;
use App\Models\Pick;
use App\Models\Soak;
use App\Models\Rinse;
use App\Models\Entry;
use App\Models\Pull;
use App\Models\Dry;
use App\Models\Product;
use App\Models\Area;
use App\Models\Container;
use App\Models\RmStock;
use App\Models\Steam;

use App\Observers\DCertificateObserver;
use App\Observers\ShapeObserver;
use App\Observers\FeatherObserver;
use App\Observers\ColorObserver;
use App\Observers\WBHouseObserver;
use App\Observers\ArrivalObserver;
use App\Observers\SupplierObserver;
use App\Observers\GradeObserver;
use App\Observers\RmGradeObserver;
use App\Observers\EdgeObserver;
use App\Observers\WashObserver;
use App\Observers\CorrectionObserver;
use App\Observers\PickObserver;
use App\Observers\SoakObserver;
use App\Observers\RinseObserver;
use App\Observers\EntryObserver;
use App\Observers\PullObserver;
use App\Observers\DryObserver;
use App\Observers\ProductObserver;
use App\Observers\AreaObserver;
use App\Observers\ContainerObserver;
use App\Observers\RmStockObserver;
use App\Observers\SteamObserver;

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

        Paginator::useBootstrap();

        Shape::observe(ShapeObserver::class);
        Feather::observe(FeatherObserver::class);
        Color::observe(ColorObserver::class);
        WBHouse::observe(WBHouseObserver::class);
        Arrival::observe(ArrivalObserver::class);
        Supplier::observe(SupplierObserver::class);
        Dcertificate::observe(DCertificateObserver::class);
        Area::observe(AreaObserver::class);
        Grade::observe(GradeObserver::class);
        GradeColor::observe(RmGradeObserver::class);
        // ProductIdentifier::observe(IdentifierObserver::class);
        Edge::observe(EdgeObserver::class);
        Wash::observe(WashObserver::class);
        Correction::observe(CorrectionObserver::class);
        Pick::observe(PickObserver::class);
        Soak::observe(SoakObserver::class);
        Rinse::observe(RinseObserver::class);
        Entry::observe(EntryObserver::class);
        Pull::observe(PullObserver::class);
        Dry::observe(DryObserver::class);
        // Product::observe(ProductObserver::class);
        Container::observe(ContainerObserver::class);
        RmStock::observe(RmStockObserver::class);
        Steam::observe(SteamObserver::class);
    }

    // 1. Grading BB (PR01GB)
    // 2. Sesek Kaki (PR02SK)
    // 3. Pencucian (PR03PC)
    // 4. Inspeksi dan Koreksi (PR04IK)
    // 5. Pencabutan Bulu (PR05PB)
    // 6. Perendaman (PR06PR)
    // 7. Cabut Bilas (PR07CB)
    // 8. Masuk Cetak (PR08MC)
    // 9. Keluar Cetak (PR09KC)
    // 10. Pengeringan (PR10PK)
    // 11. Grading PJ (PR11GP)
    // 12. Stok PJ (PR12SP)
    // 13. Steaming (PR13ST)
}
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Anugrah Alam Nestindo',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b> Anugrah Alam Nestindo </b>',
    'logo_img' => 'img/Logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'img/Logo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            // 'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'path' => 'img/Logo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => 'layout-fixed layout-navbar-fixed sidebar-collapse',
    'classes_brand' => '',
    'classes_brand_text' => 'brand-text',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    // active menu
    'classes_sidebar' => 'sidebar-dark-secondary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
        'text' => 'Dashboard',
        'url'  => 'dashboard',
        'icon' => 'fas fa-tachometer-alt',
        ],

        // Menu Master
        [
            'text' => 'Master',
            'icon' => 'fas fa-folder',
            'submenu' => [
                [   'header' => 'Admin' ],
                [   
                    'text' => 'Role',
                    'url'  => 'roles',
                    'can'  => 'Super Admin',
                    'icon' => 'fas fa-user-shield',
                ],
                [
                    'text' => 'Pengguna',
                    'url'  => 'users',
                    'can'  => 'Super Admin',
                    'icon' => 'fas fa-users',
                ],
                [   'header' => 'HRD' ],
                [
                    'text' => 'Perusahaan',
                    'url'  => 'company',
                    'can'  => ['Super Admin', 'Admin Human Resource'],
                    'icon' => 'fas fa-building',
                ],
                [
                    'text' => 'Departemen',
                    'url'  => 'departments',
                    'can'  => ['Super Admin', 'Admin Human Resource'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Jabatan',
                    'url'  => 'positions',
                    'can'  => ['Super Admin', 'Admin Human Resource'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Karyawan',
                    'url'  => 'employees',
                    'can'  => ['Super Admin', 'Admin Human Resource'],
                    'icon' => 'fas fa-users',
                ],
                [   'header' => 'Ekspor | Administrasi' ],
                [
                    'text' => 'Kategori Supplier',
                    'url'  => 'categories',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Supplier',
                    'url'  => 'suppliers',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Customer',
                    'url'  => 'customers',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Mobil',
                    'url'  => 'cars',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Area',
                    'url'  => 'areas',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Rumah Burung',
                    'url'  => 'wbhouses',
                    'can'  => ['Super Admin', 'Administrasi'],
                    'icon' => 'fas fa-users',
                ],
                // [
                //     'text' => 'Rumah Burung',
                //     'url'  => 'areas-wbhouses',
                //     'can'  => ['Super Admin', 'Administrasi'],
                //     'icon' => 'fas fa-users',
                // ],
                [   'header' => 'Bahan Baku' ],
                // [
                //     'text' => 'Kategori Grade',
                //     'url'  => 'types',
                //     'can'  => ['Super Admin', 'Admin Bahan Baku', 'Administrasi'],
                //     'icon' => 'fas fa-users',
                // ],
                [
                    'text' => 'Jenis Bentuk',
                    'url'  => 'shapes',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Jenis Bulu',
                    'url'  => 'feathers',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Jenis Warna',
                    'url'  => 'colors',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Grade Bahan Baku',
                    'url'  => 'grades',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [   'header' => 'Quality Control' ],
                [
                    'text' => 'Jenis Uji',
                    'url'  => 'testTypes',
                    'can'  => ['Super Admin', 'Admin Kontrol Kualitas'],
                    'icon' => 'fas fa-users',
                ],
                [   'header' => 'Produksi' ],
                [
                    'text' => 'Tipe Sarang',
                    'url'  => 'nests',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Petugas Pemanas',
                    'url'  => 'officers',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Grade Produk Jadi',
                    'url'  => 'fp-grades',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
            ],
        ],

        // Menu Bahan Baku
        [
            'text' => 'Bahan Baku',
            'icon' => 'fas fa-folder',
            'submenu' => [
                [
                    'text' => 'Pengiriman',
                    'url'  => 'dcertificates',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Detail Pengiriman',
                    'url'  => 'details',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [   
                    'text' => 'Kedatangan',
                    'url'  => 'arrivals',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [   
                    'text' => 'Kontainer',
                    'url'  => 'containers',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Bahan Baku',
                    'url'  => 'rawMaterials',
                    'can'  => ['Super Admin'],
                    'icon' => 'fas fa-users',
                ],
                [   
                    'text' => 'Stok Bahan Baku',
                    'url'  => 'rmstocks',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
                [   
                    'text' => 'Product Identifier',
                    'url'  => 'identifiers',
                    'can'  => ['Super Admin', 'Admin Bahan Baku'],
                    'icon' => 'fas fa-users',
                ],
            ],
        ],

        // Menu Kontrol Kualitas
        [
            'text' => 'Kontrol Kualitas',
            'icon' => 'fas fa-folder',
            'submenu' => [
                // [
                //     'text' => 'Sampel Bahan Baku',
                //     'url'  => 'rm-samples',
                //     'can'  => ['Super Admin', 'Admin Kontrol Kualitas'],
                //     'icon' => 'fas fa-users',
                // ],
                [
                    'text' => 'Hasil Uji Bahan Baku',
                    'url'  => 'rm-results',
                    'can'  => ['Super Admin', 'Admin Kontrol Kualitas'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Hasil Uji Produk Jadi',
                    'url'  => 'fp-results',
                    'can'  => ['Super Admin', 'Admin Kontrol Kualitas'],
                    'icon' => 'fas fa-users',
                ],
            ],
        ],

        [
            'text' => 'Produksi',
            'icon' => 'fas fa-folder',
            'submenu' => [
                [
                    'text' => 'Tracker',
                    'url'  => 'histories',
                    'can'  => ['Super Admin'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Sesek Kaki',
                    'url'  => 'edges',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Pencucian',
                    'url'  => 'washes',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Inspeksi dan Koreksi',
                    'url'  => 'corrections',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Pencabutan Bulu',
                    'url'  => 'picks',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Perendaman',
                    'url'  => 'soaks',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Cabut Bilas',
                    'url'  => 'rinses',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Masuk Cetak',
                    'url'  => 'entries',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Keluar Cetak',
                    'url'  => 'pulls',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Pengeringan',
                    'url'  => 'dries',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Grading Produk Jadi',
                    'url'  => 'products',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Produk Jadi',
                    'url'  => 'fp-products',
                    'can'  => ['Super Admin'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Stok Produk Jadi',
                    'url'  => 'fp-stocks',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Steaming',
                    'url'  => 'steams',
                    'can'  => ['Super Admin', 'Admin Produksi'],
                    'icon' => 'fas fa-users',
                ]
            ],
        ],

        [
            'text' => 'Laporan',
            'icon' => 'fas fa-folder',
            'submenu' => [
                [
                    'text' => 'Summary Produksi',
                    'url'  => 'on-progress',
                    'can'  => ['Super Admin'],
                    'icon' => 'fas fa-users',
                ],
            ],
        ],

        // Menu Setting
        [
            'text' => 'profile',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-user',
            'submenu' => [
                [
                    'text' => 'profile',
                    'url' => 'admin/settings',
                    'icon' => 'fas fa-fw fa-user',
                ],
                [
                    'text' => 'change_password',
                    'url' => 'admin/settings',
                    'icon' => 'fas fa-fw fa-lock',
                ],
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];

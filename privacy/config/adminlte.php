<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Membership System',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Membership&nbspSystem</b>',

    'logo_mini' => '<b>MS</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'purple',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => 'fixed',

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        'MAIN NAVIGATION',

        [
            'text' => 'Setup',
            'icon' => 'wrench',
            'permission' => 'read-kolor',
            'submenu' => [

                [
                    'text'        => 'No Transaksi Setup',
                    'url'         => 'admin/transaksisetup',
                    'icon'        => 'tags',
                    'permission' => 'read-kolor',
                ],
                
                [
                    'text'        => 'Setup Tanggal',
                    'url'         => 'admin/tanggalsetup',
                    'icon'        => 'tags',
                    'permission' => 'read-kolor',
                ],

                [
                    'text'        => 'Type Cargo',
                    'url'         => 'admin/typecargo',
                    'icon'        => 'line-chart',
                    'permission' => 'read-kolor',
                    // 'permission' => 'read-taxsetup',
                ],
                
                [
                    'text'        => 'Tax Setup',
                    'url'         => 'admin/taxsetup',
                    'icon'        => 'line-chart',
                    'permission' => 'read-kolor',
                    // 'permission' => 'read-taxsetup',
                ],

                [
                    'text'        => 'Signature',
                    'url'         => 'admin/signature',
                    'icon'        => 'pencil-square',
                    'permission' => 'read-kolor',
                    // 'permission' => 'read-signature',
                ],
            ]
        ],

        [
            'text' => 'Master Data',
            'icon' => 'server',
            'permission' => 'read-masterdata',
            'submenu' => [
                [
                    'text' => '🌀 Daftar Tenaga Kerja',
                    'url'  => 'admin/membership',
                    // 'permission'=>'read-customer',
                ],

                [
                    'text' => '♨️ Customer Internal',
                    'url'  => 'admin/customerinternal',
                    'permission'=>'read-customer',
                    'permission' => 'read-kolor',
                ],

                // [
                //     'text' => 'Membership Customer',
                //     'url'  => 'admin/membershipcustomer',
                //     'icon' => 'chrome',
                //     'permission'=>'read-customer',
                // ],

                // [
                //     'text' => '🃏 Kegiatan',
                //     'url'  => 'admin/kegiatan',
                // ],

                // [
                //     'text' => '💲 Tarif Kegiatan',
                //     'url'  => 'admin/tarifkegiatan',
                // ],

                [
                    'text' => '🔔 Alat berat',
                    'url'  => 'admin/alat',
                    'permission' => 'read-kolor',
                ],
                
                [
                    'text' => '🛀 Kapal',
                    'url'  => 'admin/kapal',
                    'permission' => 'read-kolor',
                ],

                [
                    'text' => '🚗 Mobil',
                    'url'  => 'admin/mobil',
                    'permission' => 'read-kolor',
                ],

                [
                    'text' => '👨 Sopir',
                    'url'  => 'admin/sopir',
                    'permission' => 'read-kolor',
                ],

                // [
                //     'text' => '➰ Jenis Harga',
                //     'url'  => 'admin/jenisharga',
                // ],
                
                [
                    'text' => '➰ Satuan',
                    'url'  => 'admin/satuan',
                    'permission' => 'read-kolor',
                ],

                [
                    'text' => '🔥 Operator',
                    'url'  => 'admin/operator',
                    'permission' => 'read-kolor',
                ],

                [
                    'text' => '💪 Helper',
                    'url'  => 'admin/helper',
                    'permission' => 'read-kolor',
                ],

                // [
                //     'text' => '🔅 Size Container',
                //     'url'  => 'admin/sizecontainer',
                // ],

                [
                    'text' => '🃏 Vendor',
                    'url'  => 'admin/vendor',
                    'permission' => 'read-kolor',
                ],

                [
                    'text' => '🏝️ Lokasi',
                    'url'  => 'admin/masterlokasi',
                    'permission' => 'read-kolor',
                ],

                // [
                //     'text' => '🏡 Company',
                //     'url'  => 'admin/company',
                //     'permission' => 'read-company',
                // ],
            ]

        ],

        [
            'text' => 'Transaksi',
            'icon' => 'th-list',
            'permission' => 'read-kolor',
            'submenu' => [
                // [
                //     'text' => 'Permintaan Kasbon',
                //     'url'  => 'admin/kasbon',
                //     'icon' => 'pencil-square-o',
                //     'permission'  => 'read-kasbon',
                // ],
                
                [
                    'text' => 'Job Order',
                    'url'  => 'admin/joborder',
                    'icon' => 'pencil-square-o',
                    'permission'  => 'read-jo',
                ],

                [
                    'text' => 'Pemakaian Alat Berat',
                    'url'  => 'admin/pemakaianalat',
                    'icon' => 'th',
                ],

                [
                    'text' => 'Trucking',
                    'url'  => 'admin/truckingnon',
                    'icon' => 'truck',
                    'permission'  => 'read-truckingnon',
                ],

                [
                    'text' => 'Premi Operator',
                    'url'  => 'admin/insentifoperator',
                    'icon' => 'money',
                ],

                [
                    'text' => 'Premi Helper',
                    'url'  => 'admin/insentifhelper',
                    'icon' => 'money',
                ],

                [
                    'text' => 'Hasil Bagi Usaha Sopir',
                    'url'  => 'admin/hasilbagi',
                    'icon' => 'calculator',
                    'permission'  => 'read-hasilbagi',
                ],
            ]
        ],

        [
            'text' => 'Laporan',
            'icon' => 'folder-open',
            'permission' => 'read-laporan',
            'submenu' => [

                [
                    'text'        => 'Laporan Tenaga Kerja',
                    'url'         => 'admin/laporanmember',
                    'icon'        => 'bar-chart',
                ],

                [
                    'text'        => 'Rekap Premi',
                    'url'         => 'admin/laporanpremi',
                    'icon'        => 'bar-chart',
                    'permission' => 'read-kolor',
                ],
                
                [
                    'text'        => 'Rekap HBU Sopir',
                    'url'         => 'admin/laporanrekaphbu',
                    'icon'        => 'bar-chart',
                    'permission' => 'read-kolor',
                ],
                
                [
                    'text'        => 'Laporan Laba Rugi Alat',
                    'url'         => 'admin/laporanlrmobil',
                    'icon'        => 'bar-chart',
                    'permission' => 'read-kolor',
                ],
                
            ]
        ],

        // [
        //     'text' => 'Utility',
        //     'icon' => 'calendar-check-o',
        //     'permission' => 'read-utility',
        //     'submenu' => [

        //         [
        //             'text'        => 'End Of Month',
        //             'url'         => 'admin/endofmonth',
        //             'icon'        => 'file-text',
        //             'permission'  => 'read-endofmonth',
        //         ],

        //         [
        //             'text'        => 'Re-Open | Re-Open Close',
        //             'url'         => 'admin/reopen',
        //             'icon'        => 'folder-open-o',
        //             'permission'  => 'read-reopen',
        //         ],
        //     ]
        // ],


        'ACCOUNT SETTINGS',
        [
            'text' => 'Users | Roles | Permissions',
            'url'  => 'admin/users',
            'icon' => 'user',
            'permission' => 'read-users'

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        // JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        App\Menu\MenuFilter::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => false,
        'chartjs'    => false,
    ],
];

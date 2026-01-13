<?php

return [
    'title' => 'DistroZone',
    'title_prefix' => '',
    'title_postfix' => ' - Management System',
    
    'use_ico_only' => false,
    'use_full_favicon' => false,
    
    'logo' => 'DistroZone',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'DistroZone',
    
    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,
    
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,
    
    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',
    
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,
    
    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',
    
    'use_route_url' => false,
    'dashboard_url' => '/admin/dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => null,
    'password_email_url' => null,
    'profile_url' => false,
    
    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',
    
    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'DistroZone',
            'url'  => 'customer',
            'icon' => 'fas fa-fw fa-home',
        ],
        ['header' => 'NAVIGASI'],
        [
            'text' => 'Dashboard',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'text'    => 'Master Data',
            'icon'    => 'fas fa-fw fa-database',
            'submenu' => [
                [
                    'text' => 'Brands',
                    'url'  => 'admin/brands',
                    'icon' => 'fas fa-fw fa-tags',
                ],
                [
                    'text' => 'Types',
                    'url'  => 'admin/types',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Colors',
                    'url'  => 'admin/colors',
                    'icon' => 'fas fa-fw fa-palette',
                ],
                [
                    'text' => 'Sizes',
                    'url'  => 'admin/sizes',
                    'icon' => 'fas fa-fw fa-ruler',
                ],
            ],
        ],
        [
            'text' => 'Products',
            'url'  => 'admin/products',
            'icon' => 'fas fa-fw fa-tshirt',
        ],
        [
            'text' => 'Online Orders',
            'url'  => 'admin/orders',
            'icon' => 'fas fa-fw fa-shopping-cart',
        ],
        [
            'text' => 'Users',
            'url'  => 'admin/users',
            'icon' => 'fas fa-fw fa-users',
        ],
        [
            'text'    => 'Settings',
            'icon'    => 'fas fa-fw fa-cog',
            'submenu' => [
                [
                    'text' => 'Shipping Rates',
                    'url'  => 'admin/shipping-rates',
                    'icon' => 'fas fa-fw fa-truck',
                ],
                [
                    'text' => 'Operational Hours',
                    'url'  => 'admin/operational-hours',
                    'icon' => 'fas fa-fw fa-clock',
                ],
            ],
        ],
        [
            'text' => 'Reports',
            'url'  => 'admin/reports',
            'icon' => 'fas fa-fw fa-chart-bar',
        ],
        [
            'text' => 'Customer Service',
            'url'  => 'admin/customer-service',
            'icon' => 'fas fa-fw fa-comments',
        ],
    ],
    
    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'ChartJs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js',
                ],
            ],
        ],
    ],
    
    'livewire' => false,
];
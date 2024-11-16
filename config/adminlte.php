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

    'title' => env('APP_NAME', 'Laravel'),
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

    'use_ico_only' => true,
    'use_full_favicon' => true,

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

    'logo' => '<b>CMS</b> CMVSM',
    'logo_img' => 'app/img/logo_cmvsm.png',
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
            'path' => 'app/img/logo_cmvsm.png',
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
            'path' => 'app/img/logo_cmvsm.png',
            'alt' => 'CMS COLEGIO MEDICO DE VETERINARIOS',
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
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
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
    'layout_fixed_footer' => true,
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
    'classes_auth_footer' => 'd-none',
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

    'classes_body' => '',
    'classes_brand' => 'bg-dark',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-flat',
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
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => true,
    'sidebar_collapse_remember' => true,
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
    'dashboard_url' => '/',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => false,
    'password_email_url' => false,
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
        ['header' => 'CMS CMVSM','active'=>true],
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],

        [
            'text'    => 'SISTEMA SEGURIDAD',
            'icon'    => 'fas fa-fw fa-lock',
            'submenu' => [
                [
                    'text' => 'Perfil',
                    'url'  => 'perfiles',
                    'icon' => 'fas fa-fw fa-user-secret',
                ],

                [
                    'text' => 'Usuario',
                    'url'  => 'usuarios',
                    'icon' => 'fas fa-fw fa-user',
                ],
                [
                    'text' => 'Accesos',
                    'url'  => 'privilegios',
                    'icon' => 'fas fa-fw fa-unlock-alt',
                ],
            ],
        ],
        [
            'text'    => 'MANTENIMIENTO',
            'icon'    => 'fas fa-fw fa-folder-open',
            'submenu' => [
                [
                    'text' => 'Estado Civil',
                    'url'  => 'estado_civil',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Sexo',
                    'url'  => 'sexos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Tipo Documento Identidad',
                    'url'  => 'tipo_documentos_identidad',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Tipo Curso',
                    'url'  => 'tipo_cursos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Requisito',
                    'url'  => 'requisitos',
                    'icon' => 'fas fa-fw fa-list',
                ],
            ]
        ],
        [
            'text'    => 'GESTOR DE CONTENIDOS',
            'icon'    => 'fas fa-fw fa-folder-open',
            'submenu' => [
                [
                    'text' => 'Institución',
                    'url'  => 'empresas',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Slider Principal',
                    'url'  => 'slider_principal',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Trámites',
                    'url'  => 'tramites',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Comunicados',
                    'url'  => 'comunicados',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Pronunciamientos',
                    'url'  => 'pronunciamientos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Cursos',
                    'url'  => 'cursos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Noticias',
                    'url'  => 'noticias',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Eventos',
                    'url'  => 'eventos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Junta Directiva',
                    'url'  => 'juntas_directivas',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Documentos Normativos',
                    'url'  => 'documentos_normativos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Bolsa de trabajo',
                    'url'  => 'bolsa_trabajos',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Actividades Institucionales',
                    'url'  => 'actividad_institucional',
                    'icon' => 'fas fa-fw fa-list',
                ],
            ],
        ],
        [
            'text'    => 'TRANSACCIONAL',
            'icon'    => 'fas fa-fw fa-desktop',
            'submenu' => [
                [
                    'text' => 'Colegiado',
                    'url'  => 'colegiados',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Caja',
                    'url'  => 'cajas',
                    'icon' => 'fas fa-fw fa-box',
                ],
            ],
        ],
        [
            'text'    => 'MESA DE PARTES VIRTUAL',
            'icon'    => 'fas fa-fw fa-id-card',
            'submenu' => [
                [
                    'text' => 'Recibidos',
                    'url'  => 'documentos_recibidos',
                    'icon' => 'fas fa-fw fa-folder',
                ],
                [
                    'text' => 'Respondidos',
                    'url'  => 'documentos_recibidos',
                    'icon' => 'fas fa-fw fa-folder-open',
                ],
            ],
        ],
        /*
        [
            'text'    => 'LIBRO DE RECLAMACIONES',
            'icon'    => 'fas fa-fw fa-balance-scale',
            'submenu' => [
                [
                    'text' => 'Recibidos',
                    'url'  => 'reclamos_recibidos',
                    'icon' => 'fas fa-fw fa-folder',
                ],
                [
                    'text' => 'Archivados',
                    'url'  => 'reclamos_archivados',
                    'icon' => 'fas fa-fw fa-archive',
                ],
            ],
        ],
        */
        [
            'text'    => 'COLEGIADO',
            'icon'    => 'fas fa-fw fa-graduation-cap',
            'submenu' => [
                [
                    'text' => 'Mis Datos',
                    'url'  => 'colegiado_datos_principales',
                    'icon' => 'fas fa-fw fa-address-card',
                ],
                [
                    'text' => 'Mi Curriculum',
                    'url'  => 'colegiado_cv',
                    'icon' => 'fas fa-fw fa-file',
                ],
                [
                    'text' => 'Cuotas Mensuales',
                    'url'  => 'colegiado_cuotas_mensuales',
                    'icon' => 'fas fa-fw fa-list-alt',
                ],
                [
                    'text' => 'Multas Pendientes',
                    'url'  => 'colegiado_multas_pendientes',
                    'icon' => 'fas fa-fw fa-list-alt',
                ],
            ],
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
        'Generales' => [
            "active" => true,
            "files"=>[
                [
                    "type"=>"js",
                    "asset"=>true,
                    "location"=>("js/plugins/jquery-ui.min.js")
                ],
                [
                    "type"=>"js",
                    "asset"=>true,
                    "location"=>("js/plugins/autoComplete.min.js")
                ],
                [
                    "type"=>"js",
                    "asset"=>true,
                    "location"=>("js/funciones.js")
                ],
                [
                    "type"=>"js",
                    "asset"=>true,
                    "location"=>("js/required.js")
                ],
                [
                    "type"=>"css",
                    "asset"=>true,
                    "location"=>"css/my_style.css"
                ],
                [
                    "type"=>"css",
                    "asset"=>true,
                    "location"=>("css/plugins/autoComplete.css")
                ],
            ]
        ],
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
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/plugins/sweetalert2@8.js',
                ],
            ],
        ],
        'Dropzone' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/plugins/dropzone.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/plugins/dropzone.min.css',
                ],
            ],
        ],
        'Summernote' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote.min.css',
                ],
            ],
        ],
        'Toastr' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.css',
                ],
            ],
        ],
        'waitMe' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/plugins/waitMe.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/plugins/waitMe.min.css',
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

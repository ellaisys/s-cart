<?php
return [
    'version' => '4.0-beta',
    'homepage' => 'https://s-cart.org',
    'name' => 'S-Cart',
    'title' => 'Free Open Source eCommerce for Business',
    'github' => 'https://github.com/lanhktc/s-cart',
    'email' => 'lanhktc@gmail.com',
    'settings' => [
        'api_plugin' => 1,
        'api_template' => 1,
    ],
    //This value will re-define in database with App\Providers\ScartServiceProvider
    'admin_prefix' => env('ADMIN_PREFIX', 'sc_admin'),
    //Prefix for databas. Ex sc_admin_user,...
    'db_prefix' => env('DB_PREFIX', ''),
];

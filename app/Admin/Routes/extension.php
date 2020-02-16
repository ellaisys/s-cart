<?php
$router->group(['prefix' => 'extension'], function ($router) {
    $router->get('/{extensionGroup}', 'AdminExtensionsController@index')
        ->name('admin_extension');
    $router->post('/install', 'AdminExtensionsController@install')
        ->name('admin_extension.install');
    $router->post('/uninstall', 'AdminExtensionsController@uninstall')
        ->name('admin_extension.uninstall');
    $router->post('/enable', 'AdminExtensionsController@enable')
        ->name('admin_extension.enable');
    $router->post('/disable', 'AdminExtensionsController@disable')
        ->name('admin_extension.disable');
    $router->match(['put', 'post'], '/process/{group}/{key}', 'AdminExtensionsController@process')
        ->name('admin_extension.process');
});

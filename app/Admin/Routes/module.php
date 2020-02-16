<?php
$router->group(['prefix' => 'module'], function ($router) {
    $router->get('/{moduleGroup}', 'AdminModulesController@index')->name('admin_module');
    $router->post('/install', 'AdminModulesController@install')->name('admin_module.install');
    $router->post('/uninstall', 'AdminModulesController@uninstall')->name('admin_module.uninstall');
    $router->post('/enable', 'AdminModulesController@enable')->name('admin_module.enable');
    $router->post('/disable', 'AdminModulesController@disable')->name('admin_module.disable');
    $router->match(['put', 'post'], '/process/{moduleGroup}/{module}', 'AdminModulesController@process')->name('admin_module.process');
});

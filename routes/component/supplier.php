<?php
$prefixSupplier = sc_config('PREFIX_SUPPLIER')??'supplier';

Route::group(['prefix' => $prefixSupplier], function ($router) use($suffix) {
    $router->get('/', 'ShopFront@getSuppliers')->name('suppliers');
    $router->get('/{alias}'.$suffix, 'ShopFront@productToSupplier')
        ->name('supplier');
});
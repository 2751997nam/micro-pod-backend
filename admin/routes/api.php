<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/product', 'middleware' => ['api'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::put('/store/{id?}', 'ProductController@store');
});
<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['api'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('/product', 'ProductController@store');
});
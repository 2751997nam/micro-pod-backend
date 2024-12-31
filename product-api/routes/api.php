<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['api'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/product/{id}', 'ProductController@getData');
    Route::put('/product/changed/{id}', 'ProductController@sendChangeEvent');
});
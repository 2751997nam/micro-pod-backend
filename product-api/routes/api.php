<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => ['api'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'product'], function () {
        Route::get('/{id}', 'ProductController@getData');
        Route::put('/changed/{id}', 'ProductController@sendChangeEvent');
    });

    Route::group(['prefix' => 'template'], function () {
        Route::get('/{id}', 'TemplateController@getData');
        Route::put('/changed/{id}', 'TemplateController@sendChangeEvent');
    });
});
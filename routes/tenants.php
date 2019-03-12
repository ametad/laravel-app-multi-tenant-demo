<?php

Route::middleware('web')
    ->namespace('App\\Http\\Controllers\\')
    ->group(function ()
    {
        Route::get('/', function () {
            return view('welcome');
        });

        Auth::routes();

        Route::get('/home', 'HomeController@index')->name('home');
    });
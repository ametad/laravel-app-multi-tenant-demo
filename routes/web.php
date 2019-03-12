<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Admin pages
 *
 * Must be defined first.
 */
Route::domain('homestead.test')->group(function () {

    Route::get('/', function () {
        return 'Admin page...';
    })->name('admin-landing');

});


/**
 * Landing pages are defined here.
 *
 * If the tenant website is not found, the landing pages are shown.
 */
Route::get('/', function () {
    return 'Landing page...';
})->name('landing');
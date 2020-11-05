<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('chart')->group(function () {
	Route::get('create', 'ChartController@create')->name('chart.create');
	Route::get('upload/{code}', 'ChartController@upload')->name('chart.upload.get');
	Route::post('upload/{code}', 'ChartController@upload')->name('chart.upload.post');
	Route::get('check/{code}', 'ChartController@check')->name('chart.check.get');
	Route::post('check/{code}', 'ChartController@check')->name('chart.check.post');
	Route::get('setting/{code}', 'ChartController@setting')->name('chart.setting.get');
	Route::post('setting/{code}', 'ChartController@setting_post')->name('chart.setting.post');
	Route::get('publish/{code}', 'ChartController@publish')->name('chart.publish');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

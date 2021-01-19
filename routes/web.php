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

Route::prefix('map')->group(function () {
	Route::get('create', 'MapController@create')->name('map.create');
	Route::get('source/{code}', 'MapController@source')->name('map.source.list');
	Route::post('source/{code}', 'MapController@source')->name('map.source.post');
	Route::post('upload', 'MapController@upload')->name('map.source.upload');
	Route::get('setting/{code}', 'MapController@setting')->name('map.setting.get');
	Route::post('setting/{code}', 'MapController@setting')->name('map.setting.post');
	Route::get('publish/{code}', 'MapController@publish')->name('map.publish');
	Route::get('geojson', 'MapController@geojson')->name('map.geojson');
	Route::get('legend', 'MapController@legend')->name('map.legend');
});

Route::get('login', 'SsoController@Login')->name('login');
Route::get('logout', 'SsoController@Logout')->name('logout');
Route::get('callback', 'SsoController@Callback');

Route::get('/', function () {
    return view('welcome');
})->name('home');

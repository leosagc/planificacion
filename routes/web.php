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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('instituciones','SchoolController');
Route::resource('coneis','ConeiController');
Route::resource('apafas','ApafaController');
Route::get('/importar','ImportController@index');
Route::post('/importar', 'ImportController@import')->name('import');
Route::get('/plantillas/{table}', 'ImportController@template')->name('template');
Route::get('exportar/{table}', 'ExportController@export')->name('export');

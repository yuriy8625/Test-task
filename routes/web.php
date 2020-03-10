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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::middleware('auth:web')->group(function () {

Route::namespace('Admin')->name('admin.')->prefix('admin')->group(function () {
    Route::name('employees.')->prefix('employees')->group(function () {
        Route::get('/form/{employee?}', 'EmployeeController@form')->name('form');
        Route::get('/', 'EmployeeController@index')->name('list');
        Route::post('/edit/{employee?}', 'EmployeeController@edit')->name('edit');
        Route::delete('/{employee}', 'EmployeeController@delete')->name('delete');
    });

    Route::name('position.')->prefix('position')->group(function () {
        Route::get('/form/{position?}', 'PositionController@form')->name('form');
        Route::get('/', 'PositionController@index')->name('list');
        Route::post('create', 'PositionController@create')->name('create');
        Route::post('/edit/{position}', 'PositionController@edit')->name('edit');
        Route::delete('/delete/{position}', 'PositionController@delete')->name('delete');
    });
});

});

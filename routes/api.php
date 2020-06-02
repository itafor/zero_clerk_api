<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/fetch/countries','UtilsController@getCountries');
Route::get('/fetch/state/{countryId}','UtilsController@getStates');
Route::get('/fetch/industries','UtilsController@getIndustries');

Route::group([

    'prefix' => 'auth'

], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('/update/user/profile/{id}','AuthController@updateUserProfile')->name('user.update.profile');

});

//Admin area
Route::prefix("/admin")->middleware(['admin'])->group(function(){

Route::get('/fetch/roles','RoleController@index')->name('role.index');
Route::post('/store/role','RoleController@store')->name('role.store');
Route::post('/update/role/{roleId}','RoleController@update')->name('role.update');
Route::post('/destroy/role/{roleId}','RoleController@destroyRole')->name('role.destroy');

});

Route::group([

    'prefix' => 'industry'

], function () {

    Route::post('add', 'IndustryController@addIndustries');
    Route::get('my_industries', 'IndustryController@fetchUserIndustries');
    Route::get('fetch/{id}', 'IndustryController@fetchMyIndustryById')->name('industry.fetch.id');
    Route::post('edit/{id}', 'IndustryController@edit_my_industry')->name('industry.update');

});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::post('store', 'CustomerController@store');
    Route::get('lists', 'CustomerController@listCustomers');
    Route::get('fetch/{id}', 'CustomerController@fetchCustomerById');
    Route::post('update/{id}', 'CustomerController@update');
    Route::post('destroy/{id}', 'CustomerController@destroyCustomer');

});

Route::group([
    'prefix' => 'supplier'
], function () {
    Route::post('store', 'SupplierController@store');
    Route::get('lists', 'SupplierController@listSuppliers');
    Route::get('fetch/{id}', 'SupplierController@fetchSupplierById');
    Route::post('update/{id}', 'SupplierController@update');
    Route::post('destroy/{id}', 'SupplierController@destroySupplier');

});

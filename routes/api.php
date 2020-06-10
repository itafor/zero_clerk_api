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

;
Route::get('/fetch/industries','UtilsController@getIndustries');

Route::group([

    'prefix' => 'location'

], function () {

    Route::post('store', 'LocationController@store')->middleware('permission:Add location');
    Route::get('lists', 'LocationController@listLocations')->middleware('permission:View locations');
    Route::get('fetch/{id}', 'LocationController@fetchLocationById')->middleware('permission:View location');
    Route::post('update/{id}', 'LocationController@update');
    Route::post('destroy/{id}', 'LocationController@destroyLocation');
    

    Route::get('countries', 'LocationController@getCountries');
    Route::get('country/{id}', 'LocationController@getCountry');
    Route::get('states', 'LocationController@getStates');
    Route::get('state/{id}', 'LocationController@getState');
    Route::get('state/country/{id}', 'LocationController@getStatesByCountry');

});

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


Route::group([

    'prefix' => 'role'

], function () {

    Route::post('store', 'RoleController@storeRole');
    Route::post('permission/store', 'RoleController@storePermission');
    Route::post('permission/assign', 'RoleController@assignPermissionToRole');
    Route::post('permission/remove', 'RoleController@removePermissionFromRole');
    Route::post('assign/user', 'RoleController@assignRoleToUser');
    Route::post('user/revoke', 'RoleController@revokeUserRole');
    Route::post('give/permission/user', 'RoleController@giveDirectPermissionToUser');
    Route::post('remove/user/direct/permission', 'RoleController@removeUserDirectPermission');
    Route::get('permission/lists', 'RoleController@listPermissions');
    Route::get('lists', 'RoleController@listRoles');
    Route::get('user/permission/{id}', 'RoleController@list_user_permissions');


    Route::post('assign/super_admin', 'ParentUserController@assignRoleToSuperAdmin');




});


Route::group([

    'prefix' => 'parent'

], function () {

    Route::post('user/add/sub_user', 'ParentUserController@addSubUser');
    Route::get('user/list/sub_users', 'ParentUserController@listsubUsers');

});

Route::group([

    'prefix' => 'admin'

], function () {

    Route::get('users', 'AdminController@listUsers');
    Route::get('user/{id}', 'AdminController@listUser');

});

Route::group([

    'prefix' => 'industry'

], function () {

    Route::post('add', 'IndustryController@addIndustries')->middleware('permission:Add industry');
;
    Route::get('my_industries', 'IndustryController@fetchUserIndustries')->middleware('permission:List industries');

    Route::get('fetch/{id}', 'IndustryController@fetchMyIndustryById')->name('industry.fetch.id')->middleware('permission:View industry');

    Route::post('edit/{id}', 'IndustryController@edit_my_industry')->name('industry.update')->middleware('permission:Update industry');

    Route::post('destroy/{id}', 'IndustryController@destroyIndustry')->middleware('permission:Delete industry');


});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::post('store', 'CustomerController@store')->middleware('permission:Add customer');;
    Route::get('lists', 'CustomerController@listCustomers')->middleware('permission:List customers');
    Route::get('fetch/{id}', 'CustomerController@fetchCustomerById')->middleware('permission:View customer');
    Route::post('update/{id}', 'CustomerController@update')->middleware('permission:Update customer');;
    Route::post('destroy/{id}', 'CustomerController@destroyCustomer')->middleware('permission:Delete customer');;

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

Route::group([
    'prefix' => 'product'
], function () {

    //product
    Route::post('store', 'ProductController@store');
    Route::get('lists', 'ProductController@listProducts');
    Route::get('fetch/{id}', 'ProductController@fetchProductById');
    Route::post('update/{id}', 'ProductController@updateProduct');
    Route::post('destroy/{id}', 'ProductController@destroyProduct');
    Route::get('fetch/category/{id}', 'ProductController@fetchProductsByCategoryId');
    Route::get('fetch/subcategory/{id}', 'ProductController@fetchProductsBySubCategoryId');

    //category
    Route::post('category/store', 'ProductCategoryController@store');
    Route::get('category/lists', 'ProductCategoryController@listProductCategory');
    Route::get('category/fetch/{id}', 'ProductCategoryController@fetchProductCategoryById');
    Route::post('category/update/{id}', 'ProductCategoryController@updateProductCategory');
    Route::post('category/destroy/{id}', 'ProductCategoryController@destroyProductCategory');

    //product subcategory
    Route::post('subcategory/store', 'ProductSubCategoryController@store');
    Route::get('subcategory/lists', 'ProductSubCategoryController@listProductSubCategory');
    Route::get('subcategory/fetch/{id}', 'ProductSubCategoryController@fetchProductSubCategoryById');
    Route::post('subcategory/update/{id}', 'ProductSubCategoryController@updateProductSubCategory');
    Route::post('subcategory/destroy/{id}', 'ProductSubCategoryController@destroyProductSubCategory');
    Route::get('category/{id}', 'ProductSubCategoryController@fetchProductSubCategoriesByProductCategoryId');

});

Route::group([
    'prefix' => 'category'
], function () {
    Route::post('store', 'CategoryController@store');
    Route::get('lists', 'CategoryController@listCategories');
    Route::get('fetch/{id}', 'CategoryController@fetchCategoryById');
    Route::post('update/{id}', 'CategoryController@update');
    Route::post('destroy/{id}', 'CategoryController@destroyCategory');

    Route::post('subcategory/store', 'SubCategoryController@store');
    Route::get('subcategory/lists', 'SubCategoryController@listSubCategory');
    Route::get('subcategory/fetch/{id}', 'SubCategoryController@fetchSubCategoryById');
    Route::post('subcategory/update/{id}', 'SubCategoryController@update');
    Route::post('subcategory/destroy/{id}', 'SubCategoryController@destroySubCategory');
    Route::get('subcategory/category/{id}', 'SubCategoryController@fetchSubCategoriesByCategoryId');

});



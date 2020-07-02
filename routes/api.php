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

    'prefix' => 'payment'

], function () {

    Route::post('store/{payable_id}', 'PaymentController@store')->middleware('permission:Record payment');

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

    Route::post('add', 'UsersIndustryController@addUserIndustries')->middleware('permission:Add industry');
;
    Route::get('my_industries', 'UsersIndustryController@fetchUserIndustries')->middleware('permission:List industries');

    Route::get('fetch/{id}', 'UsersIndustryController@fetchMyIndustryById')->name('industry.fetch.id')->middleware('permission:View industry');

    Route::post('edit/{id}', 'UsersIndustryController@edit_my_industry')->name('industry.update')->middleware('permission:Update industry');

    Route::post('destroy/{id}', 'UsersIndustryController@destroyUserIndustry')->middleware('permission:Delete industry');


});


Route::group([

    'prefix' => 'industry'

], function () {

    Route::post('store', 'IndustryController@store');
    Route::get('lists', 'IndustryController@fetchIndustries');
    Route::get('list/{id}', 'IndustryController@fetchIndustryById');

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
    Route::post('store', 'SupplierController@store')->middleware('permission:Add supplier');;
    Route::get('lists', 'SupplierController@listSuppliers')->middleware('permission:List suppliers');;
    Route::get('fetch/{id}', 'SupplierController@fetchSupplierById')->middleware('permission:View supplier');;
    Route::post('update/{id}', 'SupplierController@update')->middleware('permission:Update supplier');;
    Route::post('destroy/{id}', 'SupplierController@destroySupplier')->middleware('permission:Delete supplier');

});

Route::group([
    'prefix' => 'product'
], function () {

    //product
    Route::post('store', 'ProductController@store')->middleware('permission:Add product');
    Route::get('lists', 'ProductController@listProducts')->middleware('permission:List products');
    Route::get('fetch/{id}', 'ProductController@fetchProductById')->middleware('permission:View product');
    Route::post('update/{id}', 'ProductController@updateProduct')->middleware('permission:Update product');
    Route::post('destroy/{id}', 'ProductController@destroyProduct')->middleware('permission:Delete product');
    Route::get('fetch/category/{id}', 'ProductController@fetchProductsByCategoryId')->middleware('permission:View product by category');
    Route::get('fetch/subcategory/{id}', 'ProductController@fetchProductsBySubCategoryId')->middleware('permission:View product by subcategory');

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
    'prefix' => 'purchase'
], function () {

    Route::post('store', 'PurchaseController@store')->middleware('permission:add purchase');
    Route::get('lists', 'PurchaseController@listPurchases')->middleware('permission:list purchases');
    Route::get('fetch/{id}', 'PurchaseController@fetchPurchaseById')->middleware('permission:View purchase');
    Route::post('update/{id}', 'PurchaseController@update')->middleware('permission:Update purchase');
    Route::post('destroy/{id}', 'PurchaseController@destroyPurchase')->middleware('permission:Delete purchase');
});

Route::group([
    'prefix' => 'sales'
], function () {

    Route::post('store', 'SaleController@store')->middleware('permission:Add sales');
    Route::get('lists', 'SaleController@listSales')->middleware('permission:List Sales');
    Route::get('fetch/{id}', 'SaleController@fetchSaleById')->middleware('permission:View Sale');
    Route::post('update/{id}', 'SaleController@update')->middleware('permission:Update Sale');
    Route::post('destroy/{id}', 'SaleController@destroySale')->middleware('permission:Delete Sale');
    Route::get('daily', 'SaleController@daily_sales_report');
});

Route::group([
    'prefix' => 'services'
], function () {

    Route::post('store', 'ServiceController@store')->middleware('permission:Add service');
    Route::get('lists', 'ServiceController@listServices')->middleware('permission:List services');
    Route::get('fetch/{id}', 'ServiceController@fetchServiceById')->middleware('permission:View service');
    Route::post('update/{id}', 'ServiceController@update')->middleware('permission:Update service');
    Route::post('destroy/{id}', 'ServiceController@destroyService')->middleware('permission:Delete service');
});


Route::group([
    'prefix' => 'expense'
], function () {

    Route::post('store', 'ExpenseController@store');
    Route::get('lists', 'ExpenseController@listExpenses');
    Route::get('fetch/{id}', 'ExpenseController@fetchExpenseById');
    Route::post('update/{id}', 'ExpenseController@update');
    Route::post('destroy/{id}', 'ExpenseController@destroyExpense');
});

Route::group([
    'prefix' => 'report'
], function () {

    Route::post('purchase', 'ReportController@purchaseReport');
    Route::post('sales', 'ReportController@salesReport');
    Route::post('expense', 'ReportController@expenseReport');
    Route::post('gross_profit', 'ReportController@grossProfit');
    Route::post('net_profit', 'ReportController@netProfit');
   
});


Route::group([
    'prefix' => 'inventories'
], function () {

    Route::get('lists', 'InventoryController@listInventories')->middleware('permission:List Inventories');
    Route::get('fetch/{id}', 'InventoryController@fetchInventoryById')->middleware('permission:View Inventory');
    Route::post('destroy/{id}', 'InventoryController@destroyInventory')->middleware('permission:Delete inventory');
    Route::get('fetch/location/{id}', 'InventoryController@fetchInventoriesByLocation');
    Route::get('out_of_stock', 'InventoryController@inventory_out_of_stock');
});

Route::group([
    'prefix' => 'category'
], function () {
    Route::post('store', 'CategoryController@store');
    Route::get('lists', 'CategoryController@listCategories');
    Route::get('fetch/{id}', 'CategoryController@fetchCategoryById');
    Route::post('update/{id}', 'CategoryController@update');
    Route::post('destroy/{id}', 'CategoryController@destroyCategory');
    Route::get('industry/{id}', 'CategoryController@fetchCategoriesByIndustry');



    Route::post('subcategory/store', 'SubCategoryController@store');
    Route::get('subcategory/lists', 'SubCategoryController@listSubCategory');
    Route::get('subcategory/fetch/{id}', 'SubCategoryController@fetchSubCategoryById');
    Route::post('subcategory/update/{id}', 'SubCategoryController@update');
    Route::post('subcategory/destroy/{id}', 'SubCategoryController@destroySubCategory');
    Route::get('subcategory/category/{id}', 'SubCategoryController@fetchSubCategoriesByCategoryId');

});

Route::group([
    'prefix' => 'item'
], function () {

    Route::post('store', 'ItemController@store');
    Route::get('lists', 'ItemController@listItems');
    Route::get('fetch/{id}', 'ItemController@fetchItemById');
    Route::post('update/{id}', 'ItemController@update');
    Route::post('destroy/{id}', 'ItemController@destroyItem');
    Route::get('category/{id}', 'ItemController@fetchItemsByCategoryId');
    Route::get('subcategory/{id}', 'ItemController@fetchItemsBySubCategoryId');
    Route::get('industry/{id}', 'ItemController@fetchItemsByIndustry');
});


Route::group([

    'prefix' => 'transfer'

], function () {

    Route::post('item', 'TransferController@transferItem');
    Route::get('lists', 'TransferController@fetchTransfer');
    Route::get('list/{id}', 'TransferController@fetchTransferById');

});

Route::group([

    'prefix' => 'plans'

], function () {

    Route::post('store', 'SubscriptionPlanController@store');
    Route::get('lists', 'SubscriptionPlanController@listPlans');
    Route::get('list/{id}', 'SubscriptionPlanController@fetchPlanById');
    Route::post('update/{id}', 'SubscriptionPlanController@update');
    Route::post('destroy/{id}', 'SubscriptionPlanController@destroyPlan');
});


Route::group([

    'prefix' => 'subscription'

], function () {

    Route::get('buy-plan/{planUuid}', 'SubscriptionController@buy_plan');
    Route::get('buy-plan', 'SubscriptionController@redirectToGateway');
    Route::get('payment/callback', 'SubscriptionController@handleGatewayCallback')->name('payment.callback');


});
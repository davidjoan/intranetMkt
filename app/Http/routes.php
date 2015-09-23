<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(array('prefix' => 'api', 'middleware' => 'auth.basic'), function(){
    Route::resource('book_accounts', 'Frontend\BookAccountController');
    Route::resource('buy_orders', 'Frontend\BuyOrderController');
    Route::resource('cost_centers', 'Frontend\CostCenterController');
    Route::resource('divisions', 'Frontend\DivisionController');
    Route::resource('expenses', 'Frontend\ExpenseController');
    Route::resource('budgets', 'Frontend\BudgetController');
    Route::resource('expense_types', 'Frontend\ExpenseTypeController');
    Route::resource('file_formats', 'Frontend\FileFormatController');
    Route::resource('roles', 'Frontend\RoleController');
    Route::resource('users', 'Frontend\UserController');

    Route::resource('entertainments', 'Frontend\EntertainmentController');
    Route::resource('medical_campaigns', 'Frontend\MedicalCampaignController');
    Route::resource('request_attentions', 'Frontend\RequestAttentionController');

});

Route::group(array('prefix' => 'frontend', 'middleware' => 'auth.basic'), function() {

    Route::get('home', 'Frontend\HomeController@index');
    Route::get('gastos', 'Frontend\HomeController@gastos');
    Route::get('presupuestos', 'Frontend\HomeController@presupuestos');
    Route::get('detalle/{id}', 'Frontend\HomeController@detalle');
    Route::get('nuevo_gasto', 'Frontend\HomeController@nuevo_gasto');
    Route::get('gastos/portada/{expense_id}', 'Frontend\HomeController@portada');
    Route::get('gastos/exportar/{expense_id}', 'Frontend\HomeController@export');
    Route::get('gastos/exportar_xls/{expense_id}/{file_format_id}', 'Frontend\HomeController@export_xls');
    Route::get('gastos/exportar_entretenimiento_xls/{expense_id}/{file_format_id}', 'Frontend\HomeController@export_entertainment_xls');
    Route::get('gastos/exportar_campana_xls/{expense_id}/{file_format_id}', 'Frontend\HomeController@export_campaign_xls');
    Route::get('gastos/exportar_atencion_xls/{expense_id}/{file_format_id}', 'Frontend\HomeController@export_attention_xls');

    Route::get('aprobar/{expense_code}', 'Frontend\HomeController@aprobar');
    Route::get('desaprobar/{expense_code}', 'Frontend\HomeController@desaprobar');

    Route::get('cost_centers/{division_id}', 'Frontend\HomeController@cost_center');
    Route::get('/expense/cost_centers/{expense_id}', 'Frontend\HomeController@cost_center_by_expense');


    Route::get('gastos/reporte/{expense_id}', 'Frontend\HomeController@report');

    Route::post('upload_format', 'Frontend\HomeController@upload');

    Route::get('cost_center/add/{expense_id}/{cost_center_id}', 'Frontend\HomeController@agregar_centro_costo');
    Route::get('cost_center/delete/{expense_amount_id}', 'Frontend\HomeController@eliminar_centro_costo');



});
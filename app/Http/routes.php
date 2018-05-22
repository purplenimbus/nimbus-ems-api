<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//header('Access-Control-Allow-Origin: *');


$app->get('/', function () use ($app) {
    return $app->version();
});

/* Tenants */
$app->get('tenants', 'TenantController@tenants'); //List all users for a certain tenant
$app->get('{tenant_id}/users', 'TenantController@getUsers'); //List all users for a certain tenant
$app->get('{tenant_id}/users/{user_id}', 'TenantController@getUser'); //List all details for a certain user
$app->post('{tenant_id}/users/{user_id}', 'TenantController@newUser'); 
$app->put('{tenant_id}/users/{user_id}', 'TenantController@updateUser'); 
/* Payroll */
$app->get('{tenant_id}/payrolls/', 'PayrollController@getPayrolls'); //Get all payroll for a certain tenant
$app->get('{tenant_id}/payrolls/{company_payroll_id}', 'PayrollController@getPayroll'); //Get a specific payroll for a certain tenant
$app->post('{tenant_id}/payrolls/{company_payroll_id}', 'PayrollController@batchProcessPayroll');//batch proces payroll 
 
//$app->get('{tenant_id}/users/{user_id}/tasks', 'TenantController@userTasks'); //List all tasks for a certain user
//$app->get('{tenant_id}/activities/', 'TenantController@activities'); //List all activities for a certain tenant

/* Auth */
$app->post('{tenant_id}/login', 'AuthenticateController@authenticate'); //Login a user to a certain tenant
$app->options('{tenant_id}/login', function(){
	return 'Hello World';
}); //Login a user to a certain tenant
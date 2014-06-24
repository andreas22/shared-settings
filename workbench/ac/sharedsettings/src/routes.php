<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array( 'prefix' => 'admin/sharedsettings',
    'before' => array('logged', 'can_see')), function()
{
    // Data
    Route::get('data/list', array('as' => 'sharedsettings.data.list', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@index'));
    Route::get('data/new', array('as' => 'sharedsettings.data.new', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@edit'));
    Route::get('data/view/{id}', array('as' => 'sharedsettings.data.view', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@view'));
    Route::get('data/edit/{id}', array('as' => 'sharedsettings.data.edit', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@edit'));
    Route::post('data/save', array("before" => "csrf", 'as' => 'sharedsettings.data.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@save'));
    Route::get('data/delete/{id}', array('as' => 'sharedsettings.data.delete', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@delete'));

    // API Users
    Route::get('apiuser/list', array('as' => 'sharedsettings.apiuser.list', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@index'));
    Route::get('apiuser/new', array('as' => 'sharedsettings.apiuser.new', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@edit'));
    Route::get('apiuser/view/{id}', array('as' => 'sharedsettings.apiuser.view', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@view'));
    Route::get('apiuser/edit/{id}', array('as' => 'sharedsettings.apiuser.edit', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@edit'));
    Route::post('apiuser/save', array("before" => "csrf", 'as' => 'sharedsettings.apiuser.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@save'));
    Route::get('apiuser/delete/{id}', array('as' => 'sharedsettings.apiuser.delete', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@delete'));
    Route::post('apiuser/permissions/save', array("before" => "csrf", 'as' => 'sharedsettings.apiuser.permissions.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@permissionsSave'));

});

Route::group(array( 'prefix' => 'api',
    'before' => array('validate_data_code_exists',
                      'api_validate_ip',
                      'api_validate_permissions',
                      'api_validate_credentials')), function()
{
    Route::post('get', array('as' => 'sharedsettings.api.get', 'uses' => 'Ac\SharedSettings\Controllers\Api\DataController@get'));
});

Route::group(array( 'prefix' => 'admin',
    'before' => array('logged', 'can_see', 'validate_data_code_exists', 'api_validate_ip')), function()
{
    Route::get('test', array('as' => 'sharedsettings.test', 'uses' => 'Ac\SharedSettings\Controllers\Admin\TestController@index'));
});
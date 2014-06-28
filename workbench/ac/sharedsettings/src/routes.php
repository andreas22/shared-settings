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

/*
 * ADMIN ROUTES
 */
Route::group(array( 'prefix' => 'admin/sharedsettings',
    'before' => array('logged', 'can_see')), function()
{
    // Data
    Route::get('data/list', array('as' => 'data.list', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@index'));
    Route::get('data/new', array('as' => 'data.new', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@edit'));
    Route::get('data/view/{id}', array('as' => 'data.view', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@view'));
    Route::get('data/edit/{id}', array('as' => 'data.edit', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@edit'));
    Route::post('data/save', array("before" => "csrf", 'as' => 'data.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@save'));
    Route::get('data/delete/{id}', array('as' => 'data.delete', 'uses' => 'Ac\SharedSettings\Controllers\Admin\DataController@delete'));

    // API Users
    Route::get('apiuser/list', array('as' => 'apiuser.list', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@index'));
    Route::get('apiuser/new', array('as' => 'apiuser.new', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@edit'));
    Route::get('apiuser/view/{id}', array('as' => 'apiuser.view', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@view'));
    Route::get('apiuser/edit/{id}', array('as' => 'apiuser.edit', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@edit'));
    Route::post('apiuser/save', array("before" => "csrf", 'as' => 'apiuser.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@save'));
    Route::get('apiuser/delete/{id}', array('as' => 'apiuser.delete', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@delete'));
    Route::post('apiuser/permissions/save', array("before" => "csrf", 'as' => 'apiuser.permissions.save', 'uses' => 'Ac\SharedSettings\Controllers\Admin\ApiUsersController@permissionsSave'));

});

/*
 * API ROUTES
 */
Route::group(array( 'prefix' => 'api',
                    'before' => array('validate_data_code_exists')), function()
{
    //Public Data
    Route::group(array('before' => array('api_is_private')), function()
    {
        Route::get('get', array('as' => 'api.public.get', 'uses' => 'Ac\SharedSettings\Controllers\Api\DataController@get'));
    });

    //Private Data
    Route::group(array( 'before' => array(
                            'api_validate_permissions',
                            'api_validate_credentials',
                            'api_validate_ip'
                        )), function()
    {
        Route::post('get', array('as' => 'api.private.get', 'uses' => 'Ac\SharedSettings\Controllers\Api\DataController@get'));
    });
});

Route::group(array( 'prefix' => 'admin',
    'before' => array('logged', 'can_see', 'validate_data_code_exists', 'api_validate_ip')), function()
{
    Route::get('test', array('as' => 'test', 'uses' => 'Ac\SharedSettings\Controllers\Admin\TestController@index'));
});
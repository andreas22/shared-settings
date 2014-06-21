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

Route::get('/', function()
{
	return View::make('hello');
});


Route::group(array( 'prefix' => 'admin/sharedsettings',
                    'before' => array('logged', 'can_see')), function()
{
    // Data
    Route::get('data/list', array('as' => 'sharedsettings.data.list', 'uses' => 'AdminDataController@index'));
    Route::get('data/new', array('as' => 'sharedsettings.data.new', 'uses' => 'AdminDataController@edit'));
    Route::get('data/view/{id}', array('as' => 'sharedsettings.data.view', 'uses' => 'AdminDataController@view'));
    Route::get('data/edit/{id}', array('as' => 'sharedsettings.data.edit', 'uses' => 'AdminDataController@edit'));
    Route::post('data/save', array("before" => "csrf", 'as' => 'sharedsettings.data.save', 'uses' => 'AdminDataController@save'));
    Route::get('data/delete/{id}', array('as' => 'sharedsettings.data.delete', 'uses' => 'AdminDataController@delete'));

    // API Users
    Route::get('apiuser/list', array('as' => 'sharedsettings.apiuser.list', 'uses' => 'AdminApiUsersController@index'));
    Route::get('apiuser/new', array('as' => 'sharedsettings.apiuser.new', 'uses' => 'AdminApiUsersController@edit'));
    Route::get('apiuser/view/{id}', array('as' => 'sharedsettings.apiuser.view', 'uses' => 'AdminApiUsersController@view'));
    Route::get('apiuser/edit/{id}', array('as' => 'sharedsettings.apiuser.edit', 'uses' => 'AdminApiUsersController@edit'));
    Route::post('apiuser/save', array("before" => "csrf", 'as' => 'sharedsettings.apiuser.save', 'uses' => 'AdminApiUsersController@save'));
    Route::get('apiuser/delete/{id}', array('as' => 'sharedsettings.apiuser.delete', 'uses' => 'AdminApiUsersController@delete'));
    Route::post('apiuser/permissions/save', array("before" => "csrf", 'as' => 'sharedsettings.apiuser.permissions.save', 'uses' => 'AdminApiUsersController@permissionsSave'));
});

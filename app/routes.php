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
    Route::get('data/list', array('as' => 'sharedsettings.data.list', 'uses' => 'AdminDataController@index'));
    Route::get('data/view/{id}', array('as' => 'sharedsettings.data.view', 'uses' => 'AdminDataController@view'));
    Route::get('data/edit', array('as' => 'sharedsettings.data.new', 'uses' => 'AdminDataController@edit'));
    Route::get('data/edit/{id}', array('as' => 'sharedsettings.data.edit', 'uses' => 'AdminDataController@edit'));
    Route::post('data/edit', array('as' => 'sharedsettings.data.save', 'uses' => 'AdminDataController@save'));
    Route::get('data/delete/{id}', array('as' => 'sharedsettings.data.delete', 'uses' => 'AdminDataController@delete'));

});

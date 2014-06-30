<?php

use Ac\SharedSettings\Common\IPServices;

Route::filter('validate_data_code_exists', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfDataCodeExists(Input::get('code'));

    if($result['result']['status'] != 200)
        return Response::json($result);
});

Route::filter('api_is_private', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfDataIsPrivate(Input::get('code'));

    if($result['result']['status'] != 200)
        return Response::json($result);
});

Route::filter('api_apiuser_is_active', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserIsActive(Input::get('username'));

    if($result['result']['status'] != 200)
        return Response::json($result);
});

Route::filter('api_validate_ip', function()
{
    $ip = IPServices::getIPAddress();
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfIncomingIPAllowed(Input::get('username'), $ip);

    if($result['result']['status'] != 200)
        return Response::json($result);
});

Route::filter('api_validate_permissions', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserHasPermissions(Input::get('username'), Input::get('code'));

    if($result['result']['status'] != 200)
        return Response::json($result);
});

Route::filter('api_validate_credentials', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserValidCredentials(Input::get('username'), Input::get('secret'));

    if($result['result']['status'] != 200)
        return Response::json($result);
});


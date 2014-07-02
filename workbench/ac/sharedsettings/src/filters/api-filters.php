<?php

use Ac\SharedSettings\Common\IPServices;
use Ac\SharedSettings\Common\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

Route::filter('validate_data_code_exists', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfDataCodeExists(Input::get('code'));
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});

Route::filter('api_is_private', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfDataIsPrivate(Input::get('code'));
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});

Route::filter('api_apiuser_is_active', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserIsActive(Input::get('username'));
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});

Route::filter('api_validate_ip', function()
{
    $ip = IPServices::getIPAddress();
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfIncomingIPAllowed(Input::get('username'), $ip);
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});

Route::filter('api_validate_permissions', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserHasPermissions(Input::get('username'), Input::get('code'));
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});

Route::filter('api_validate_credentials', function()
{
    $filter = App::make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');
    $result = $filter->validateIfApiuserValidCredentials(Input::get('username'), Input::get('secret'));
    $suppress_response_codes = Input::get('suppress_response_codes');

    if($result['result']['status'] != HttpCodes::HTTP_FOUND)
        return JsonResponse::withError($result, $suppress_response_codes);
});


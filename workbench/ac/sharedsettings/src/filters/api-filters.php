<?php

use Ac\SharedSettings\Common\IPServices;

Route::filter('validate_data_code_exists', function()
{
    $code = Input::get('code');
    $dataExists = \Ac\SharedSettings\Models\Data::viewApiUserDataByCode($code);

    if(!$dataExists)
        return Response::json(
            ['result' => [
                'status' => '0',
                'data' => null,
                'error' => 'Invalid code request!']
        ]);
});

/*
 * Single: 192.168.0.1
 * List (comma separated): 192.168.0.1,192.168.0.2
 * Range (from-to): 192.168.0.1-192.168.0.255
 * Any (with asterisk): *
 *
 */
Route::filter('api_validate_ip', function()
{
    $found = false;
    $ip = IPServices::getIPAddress();
    $code = Input::get('code');
    $dataExists = \Ac\SharedSettings\Models\Data::viewApiUserDataByCode($code);

    foreach($dataExists as $obj)
    {
        if( $obj->address == $ip /*Exact match*/ ||
            strstr($obj->address, $ip) /*In a list*/ ||
            $obj->address == '*' /*Any ip is allowed*/
            )
        {
            $found = true;
        }

        /*Inside a given range*/
        if(strstr($obj->address, '-'))
        {
            $ip = ip2long($ip);
            $ip_range = explode('-', $obj->address);
            $from = ip2long($ip_range[0]);
            $to = ip2long($ip_range[1]);

            if($ip >= $from || $ip <= $to)
                $found = true;
        }
    }

    if(!$found)
        return Response::json(
            ['result' => [
                'status' => '0',
                'data' => null,
                'error' => 'Invalid IP address!']
            ]);
});

Route::filter('api_validate_permissions', function()
{
    $found = false;
    $username = Input::get('username');
    $code = Input::get('code');
    $dataExists = \Ac\SharedSettings\Models\Data::viewApiUserDataByCode($code);

    foreach($dataExists as $obj)
    {
        if($obj->username == $username)
        {
            $found = true;
            break;
        }
    }

    if(!$found)
        return Response::json(
            ['result' => [
                'status' => '0',
                'data' => null,
                'error' => 'Inefficient permissions!']
            ]);
});

Route::filter('api_validate_credentials', function()
{
    $found = false;
    $username = Input::get('username');
    $secret = md5(Input::get('secret'));
    $code = Input::get('code');
    $dataExists = \Ac\SharedSettings\Models\Data::viewApiUserDataByCode($code);

    foreach($dataExists as $obj)
    {
        if($obj->username == $username && $obj->secret == $secret)
        {
            $found = true;
            break;
        }
    }

    if(!$found)
        return Response::json(
            ['result' => [
                'status' => '0',
                'data' => null,
                'error' => 'Invalid credentials!']
            ]);
});


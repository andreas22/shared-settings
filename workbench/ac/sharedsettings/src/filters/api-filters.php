<?php
Route::filter('api_validate_ip', function()
{
    throw new Exception('Filter api_validate_ip called!');
});

Route::filter('api_validate_credentials', function()
{
    throw new Exception('Filter api_validate_credentials called!');
});

Route::filter('api_validate_permissions', function()
{
    throw new Exception('Filter api_validate_permissions called!');
});


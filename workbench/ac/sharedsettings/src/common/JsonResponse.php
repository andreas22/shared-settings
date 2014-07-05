<?php namespace Ac\SharedSettings\Common;

use Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;
use Log;

class JsonResponse
{
    /**
     * Return a json response with error message and status code as specified
     *
     * @param $result
     * @param $suppress_response_codes
     * @return \Illuminate\Http\JsonResponse
     */
    public static function withError($result, $suppress_response_codes = 0)
    {
        $status = $suppress_response_codes ? HttpCodes::HTTP_OK : $result['result']['status'];
        $error = isset($result['result']['error']) ? $result['result']['error'] : '';
        if(isset($_REQUEST['secret']))
            $_REQUEST['secret'] = '******';
        Log::warning(sprintf("Status code (%s) | Real Status code (%s) | Error (%s) | Request: (%s)", $status, $result['result']['status'], $error, json_encode($_REQUEST)));
        return Response::json(['response_code' => $result['result']['status'],
                               'message' => $error],$status);
    }

    /**
     * Return a json response with data and status code as specified
     *
     * @param $result
     * @param $suppress_response_codes
     * @return \Illuminate\Http\JsonResponse
     */
    public static function withSuccess($result, $suppress_response_codes = 0)
    {
        $status = $suppress_response_codes ? HttpCodes::HTTP_OK : $result['result']['status'];
        $data = isset($result['result']['data']) ? $result['result']['data'] : '';
        if(isset($_REQUEST['secret']))
            $_REQUEST['secret'] = '******';
        Log::info(sprintf("Status code (%s) | Real Status code (%s) | Request: (%s)", $status, $result['result']['status'], json_encode($_REQUEST)));
        return Response::json($data, $status);
    }
} 
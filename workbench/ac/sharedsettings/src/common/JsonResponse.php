<?php namespace Ac\SharedSettings\Common;

use Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

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
        return Response::json($data, $status);
    }
} 
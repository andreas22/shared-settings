<?php namespace Ac\SharedSettings\Common;

use Response;

class JsonResponse
{
    /**
     * Return a json response with error message and status code as specified
     *
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public static function withError($result)
    {
        $error = isset($result['result']['error']) ? $result['result']['error'] : '';
        return Response::json(['error' => $error], $result['result']['status']);
    }

    /**
     * Return a json response with data and status code as specified
     *
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public static function withSuccess($result)
    {
        $data = isset($result['result']['data']) ? $result['result']['data'] : '';
        return Response::json(['data' => $data], $result['result']['status']);
    }
} 
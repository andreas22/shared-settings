<?php namespace Ac\SharedSettings\Controllers\Api;

use Ac\SharedSettings\Models\Data;
use Input;
use Response;

class DataController extends \Controller
{
    /**
     * Return data based on the given code
     * If parameter p=1 then returns data content only
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $code = Input::get('code');
        $param = Input::get('p');

        $data = Data::where('code', '=', $code)->first();

        if($data != null)
        {
            $response = ['result' => [
                            'status' => '200',
                            'data' => $data->content,
                            'error' => null]
                        ];

            if(!empty($param))
                $response = json_decode($data->content);
            return Response::json($response);
        }

        return Response::json(
            ['result' => [
                'status' => '404',
                'data' => null,
                'error' => 'Data with code ' . $code . ' cannot be found!']
            ]);
    }
}

<?php namespace Ac\SharedSettings\Controllers\Api;

use Ac\SharedSettings\Models\Data;
use Input;
use Response;

class DataController extends \Controller
{
    /**
     * Return data based on the given code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
	{
        $code = Input::get('code');
        $data = Data::where('code', '=', $code)->first();

        if($data != null)
            return Response::json(
                ['result' => [
                    'status' => '1',
                    'data' => $data,
                    'error' => null]
                ]);

        return Response::json(
            ['result' => [
                'status' => '0',
                'data' => null,
                'error' => 'Data with code ' . $code . ' cannot be found!']
            ]);
	}
}

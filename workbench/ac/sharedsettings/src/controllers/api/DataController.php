<?php namespace Ac\SharedSettings\Controllers\Api;

use Ac\SharedSettings\Repositories\DataRepositoryInterface;
use Input;
use Response;
use DB;

class DataController extends \Controller
{
    /*
     * Ac\SharedSettings\Repositories\DataRepositoryInterface
     */
    private $data;

    public function __construct(DataRepositoryInterface $data)
    {
        $this->data = $data;
    }
    /**
     * Return data based on the given code
     * If parameter p=1 then returns data content only
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $data = $this->data->findByCode(Input::get('code'));

        if($data != null)
        {
            $response = ['result' => [
                            'status' => '200',
                            'data' => $data->content,
                            'error' => null]
                        ];

            if(Input::has('p'))
                $response = json_decode($data->content);
            return Response::json($response);
        }

        return Response::json(
            ['result' => [
                'status' => '404',
                'data' => null,
                'error' => 'Data with code ' . Input::get('code') . ' cannot be found!']
            ]);
    }
}

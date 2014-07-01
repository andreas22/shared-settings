<?php namespace Ac\SharedSettings\Controllers\Api;

use Input;
use DB;
use Symfony\Component\HttpFoundation\Response as HttpCodes;
use Ac\SharedSettings\Repositories\DataRepositoryInterface;
use Ac\SharedSettings\Common\JsonResponse;
use Ac\SharedSettings\Common\ArrayHelper;

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
            $content = json_decode($data->content, true);
            $response = ['result' => [
                            'status' => HttpCodes::HTTP_FOUND,
                            'data' => $content]
                        ];

            if(Input::has('tag'))
                $response['result']['data'] = [Input::get('tag') => ArrayHelper::getValueByKey( Input::get('tag'), $content )];

            return JsonResponse::withSuccess($response);
        }

        $result = ['result' => [
            'status' => '404',
            'error' => 'Data cannot be found!']
        ];

        return JsonResponse::withError($result, HttpCodes::HTTP_NOT_FOUND);
    }
}

<?php namespace Ac\SharedSettings\Controllers\Admin;

use Ac\SharedSettings\Models\Data;
use Ac\SharedSettings\Models\ApiUser;
use URL;
use Validator;
use Redirect;
use View;
use Input;
use App;
use DB;

class TestController extends \Controller {



    public function __construct()
    {

    }

	public function index()
	{
        echo "<pre>";

        /*$a = ApiUser::find(5)->data;
        foreach($a as $d)
        {
            echo "<br />" . $d->created_at;
        }*/

        $code = 33;
        $username = 'domino';
        $secret = '1234';
        $ip = '1.21.1.1';

        $result = DB::table('apiuser_data')
            ->join('apiusers', 'apiusers.id', '=', 'apiuser_data.apiuser_id')
            ->join('data', 'data.id', '=', 'apiuser_data.data_id')
            ->select('apiusers.address', 'apiusers.username', 'apiusers.secret')
            ->where('data.code', '=', $code)
            ->get();

        print_r($result);

        $queries = DB::getQueryLog();
        $last_query = end($queries);
        print_r($last_query);



        //$users = DB::table('users')->select('name', 'email')->get();
        //print_r($a);
      /*  foreach ($a->apiusers as $apiusers)
        {
            echo $apiusers->pivot->created_at;
        }*/



        exit();
	}
}

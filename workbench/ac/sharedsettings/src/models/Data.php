<?php namespace Ac\SharedSettings\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use DB;

class Data extends \Eloquent {

    public static $rules = array(
        'code' => 'required',
        'title' => 'required'
    );

    public static $results;

    public static $code;

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'data';

    protected $fillable = array('title', 'code', 'description', 'content', 'modified_by', 'created_by');

    public function apiusers()
    {
        return $this->belongsToMany('Ac\SharedSettings\Models\ApiUser', 'apiuser_data', 'apiuser_id', 'data_id');
    }

    public function createdBy()
    {
        return $this->hasOne('User', 'id', 'created_by');
    }

    public function modifiedBy()
    {
        return $this->hasOne('User', 'id', 'modified_by');
    }

    public static function viewApiUserDataByCode($code)
    {
        if(Data::$results == null || Data::$code != $code)
        {
            Data::$results = DB::table('apiuser_data')
                                ->join('apiusers', 'apiusers.id', '=', 'apiuser_data.apiuser_id')
                                ->join('data', 'data.id', '=', 'apiuser_data.data_id')
                                ->select('apiusers.address', 'apiusers.username', 'apiusers.secret', 'data.private')
                                ->where('data.code', '=', $code)
                                ->get();
            Data::$code = $code;

            $queries = DB::getQueryLog();
            $last_query = end($queries);
            \Log::info($last_query['query']);
        }

        return Data::$results;
    }
}

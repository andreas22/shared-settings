<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ApiUser extends \Eloquent {

    public static $rules = array(
        'username' => 'required',
        'secret' => 'required',
        'address' => 'required'
    );

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'apiusers';

    protected $fillable = array('username', 'secret', 'description', 'callback_url', 'address', 'modified_by', 'created_by');

    public function data()
    {
        return $this->belongsToMany('Data');
    }

    public function createdBy()
    {
        return $this->hasOne('User', 'id', 'created_by');
    }

    public function modifiedBy()
    {
        return $this->hasOne('User', 'id', 'modified_by');
    }

}

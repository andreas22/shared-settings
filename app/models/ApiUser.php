<?php

class ApiUser extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'apiusers';

    protected $fillable = array('name', 'callback_url', 'address', 'modified_by', 'created_by');

    public function settings()
    {
        return $this->belongsToMany('Setting');
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

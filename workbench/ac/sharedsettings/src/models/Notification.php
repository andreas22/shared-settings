<?php namespace Ac\SharedSettings\Models;

class Notification extends \Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    public function createdBy()
    {
        return $this->hasOne('User', 'id', 'created_by');
    }

    public function data()
    {
        return $this->hasOne('Data', 'id', 'data_id');
    }
}
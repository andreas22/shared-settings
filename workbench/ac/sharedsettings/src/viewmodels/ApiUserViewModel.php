<?php namespace Ac\SharedSettings\ViewModels;

class ApiUserViewModel
{
    public $id;
    public $description;
    public $callback_url;
    public $address;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $modified_by;
    public $deleted_at;
    public $secret;
    public $username;
    public $active = 0;
    public $created_by_email;
    public $modified_by_email;
    public $permissions;

    public function init(\Ac\SharedSettings\Models\ApiUser $model = null)
    {
        if($model)
        {
            $this->id = $model->id;
            $this->description = $model->description;
            $this->callback_url = $model->callback_url;
            $this->address = $model->address;
            $this->created_at = $model->created_at;
            $this->updated_at = $model->updated_at;
            $this->created_by = $model->created_by;
            $this->modified_by = $model->modified_by;
            $this->deleted_at = $model->deleted_at;
            $this->secret = $model->secret;
            $this->username = $model->username;
            $this->active = $model->active;
            $this->permissions = $model->data;
        }
    }
} 
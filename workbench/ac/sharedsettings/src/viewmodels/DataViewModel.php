<?php namespace Ac\SharedSettings\ViewModels;

class DataViewModel 
{        
    public $id;
    public $code;
    public $title;
    public $description;
    public $content;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $modified_by;
    public $deleted_at;
    public $private;
    public $created_by_email;
    public $modified_by_email;
    public $hasPendingNotifications;
    public $apiusers;

    public function init(\Ac\SharedSettings\Models\Data $data = null)
    {
        if($data)
        {
            $this->id = $data->id;
            $this->code = $data->code;
            $this->content = $data->content ? $data->content : '{}';
            $this->created_at = $data->created_at;
            $this->deleted_at = $data->deleted_at;
            $this->created_by = $data->created_by;
            $this->description = $data->description;
            $this->modified_by = $data->modified_by;
            $this->private = $data->private;
            $this->title = $data->title;
            $this->updated_at = $data->updated_at;
            $this->created_by_email = $data->createdBy->email;
            $this->modified_by_email = $data->modifiedBy->email;
            $this->apiusers = $data->apiusers;

        }
        else
        {
            $this->code = 'auto';
            $this->content = '{}';
        }
    }
} 
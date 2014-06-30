<?php namespace Ac\SharedSettings\ViewModels;

class PaginateViewModel
{        
    public $list;
    public $links;

    public function add($model)
    {
        $this->list[] = $model;
    }
} 
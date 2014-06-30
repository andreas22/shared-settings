<?php namespace Ac\SharedSettings\Controllers\Admin;

use URL;
use Redirect;
use View;
use Input;
use Ac\SharedSettings\ViewModels\PaginateViewModel;
use Ac\SharedSettings\ViewModels\ApiUserViewModel;
use Ac\SharedSettings\ViewModels\ApiUserPermissionsViewModel;
use Ac\SharedSettings\Repositories\APIUsersRepositoryInterface;
use Ac\SharedSettings\Repositories\DataRepositoryInterface;

class ApiUsersController extends \Controller {

    private $sidebar;

    /*
     * @var Ac\SharedSettings\Repositories\APIUsersRepositoryInterface
     */
    private $apiuser;

    /*
     * @var Ac\SharedSettings\Repositories\DataRepositoryInterface
     */
    private $data;

    public function __construct(APIUsersRepositoryInterface $apiuser, DataRepositoryInterface $data)
    {
        $this->apiuser = $apiuser;
        $this->data = $data;
        $count = $this->apiuser->total();
        $this->sidebar = array(
            "API Users List <span class=\"badge\">$count</span>" => array('url' => URL::route('apiuser.list'), 'icon' => '<i class="fa fa-users"></i>'),
            'Add New' => array('url' => URL::route('apiuser.new'), 'icon' => '<i class="fa fa-plus-circle"></i>'),
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $apiuser = $this->apiuser->all(15);

        $model = new PaginateViewModel();

        foreach($apiuser as $d)
        {
            $m = new ApiUserViewModel();
            $m->init($d);
            $model->add($m);
        }

        $model->links = $apiuser->links();

        return View::make('sharedsettings::admin.apiuser.index', array('model' => $model))
            ->with('sidebar_items', $this->sidebar);
    }

    /**
     * View an API User
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id = 0)
    {
        $apiuser = $this->apiuser->find($id);
        $model = new ApiUserViewModel();
        $model->init($apiuser);
        return View::make('sharedsettings::admin.apiuser.view', array('model' => $model))
            ->with('sidebar_items', $this->sidebar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id = 0)
    {
        $apiuser = $this->apiuser->find($id);
        $model = new ApiUserViewModel();
        $model->init($apiuser);
        $permission_values_tmp = array();
        $permission_values = $this->data->all(10000);
        $user_data_codes = array();
        $apiuser_data = $model->permissions;

        if($apiuser)
        {
            foreach($apiuser->data as $v)
            {
                $user_data_codes[] = $v->code;
            }
        }

        foreach($permission_values as $v)
        {
            if(!in_array($v->code, $user_data_codes))
                $permission_values_tmp[$v->id] = sprintf('[%s] %s', $v->code, $v->title);
        }

        $permModel = new ApiUserPermissionsViewModel();
        $permModel->api_user_id = $model->id;
        $permModel->api_user_permissions = $apiuser_data;
        $permModel->available_permissions = $permission_values_tmp;

        return View::make('sharedsettings::admin.apiuser.edit',
            array('model' => $model,
                  'perm_model' => $permModel))
            ->with('sidebar_items', $this->sidebar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function save()
    {
        $validator = $this->apiuser->validate(Input::all());

        if ($validator)
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();

        //Edit
        if(Input::has('id'))
        {
            $id = Input::get('id');
            $this->apiuser->save(Input::all());
        }
        //New
        else
        {
            $userExists = $this->apiuser->findByUsername(Input::get('username'));

            if($userExists)
            {
                return Redirect::back()
                    ->withErrors(array('Username is already in use! Please choose a different username and try again.'))
                    ->withInput();
            }

            $id = $this->apiuser->create(Input::all());
        }

        return Redirect::route('apiuser.edit', array('id' => $id))->with('message', "Successfully saved!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $result = $this->apiuser->delete($id);
        $message = ($result) ? 'Deleted successfully' : 'Failed to delete';
        return Redirect::route('apiuser.list')->with('message', $message);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permissionsSave()
    {
        $message = '';
        $api_user_id = Input::get('api_user_id');
        $data_id = Input::get('data_id');

        switch(Input::get('operation'))
        {
            //Delete
            case 0:
                $this->apiuser->removePermission($api_user_id, $data_id);
                $message = 'Permission deleted successfully';
                break;

            //Add
            case 1:
                $this->apiuser->addPermission($api_user_id, $data_id);
                $message = 'Permission added successfully';
                break;
        }

        return Redirect::route('apiuser.edit', array('id' => $api_user_id))->with('message', $message);
    }
}
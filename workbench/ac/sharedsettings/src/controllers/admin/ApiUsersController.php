<?php namespace Ac\SharedSettings\Controllers\Admin;

use Ac\SharedSettings\Models\ApiUser;
use Ac\SharedSettings\Models\Data;
use URL;
use Validator;
use Redirect;
use View;
use Input;
use App;

class ApiUsersController extends \Controller {

    private $sidebar;

    public function __construct()
    {
        $count = ApiUser::all()->count();

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
        $apiuser = ApiUser::paginate(15);
        return View::make('sharedsettings::admin.apiuser.index', array('apiuser' => $apiuser))->with('sidebar_items', $this->sidebar);
    }

    /**
     * View an API User
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id = 0)
    {
        $apiuser = ApiUser::find($id);
        return View::make('sharedsettings::admin.apiuser.view', array('apiuser' => $apiuser))->with('sidebar_items', $this->sidebar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id = 0)
    {
        $apiuser = $id ? ApiUser::find($id) : new ApiUser();
        $permission_values_tmp = array();
        $permission_values = Data::all();
        $user_data_codes = array();

        foreach($apiuser->data as $v)
        {
            $user_data_codes[] = $v->code;
        }

        foreach($permission_values as $v)
        {
            if(!in_array($v->code, $user_data_codes))
                $permission_values_tmp[$v->id] = sprintf('[%s] %s', $v->code, $v->title);
        }

        return View::make('sharedsettings::admin.apiuser.edit', array('apiuser' => $apiuser,
                                                      'permission_values' => $permission_values_tmp,
                                                      'user_acl' => $apiuser->data))
            ->with('sidebar_items', $this->sidebar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function save()
    {
        $validator = Validator::make(Input::all(), ApiUser::$rules);

        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $id = Input::get('id');
        $description = Input::get('description');
        $callback_url = Input::get('callback_url');
        $address = Input::get('address');
        $username = Input::get('username');
        $secret = Input::get('secret');
        $active = (int) Input::get('active');

        //Edit
        if($id)
        {
            $apiuser = ApiUser::find($id);
            $apiuser->active = $active;
            $apiuser->description = $description;
            $apiuser->username = $username;
            if(!empty($secret) &&
                ($apiuser->secret != md5($secret) && $apiuser->secret != $secret))
                $apiuser->secret = md5($secret);
            $apiuser->callback_url = $callback_url;
            $apiuser->address = $address;
            $apiuser->modified_by = App::make('authenticator')->getLoggedUser()->id;
            $apiuser->save();
        }
        //New
        else
        {
            $userExists = ApiUser::where('username', '=', $username)->count();
            if($userExists)
            {
                return Redirect::back()
                    ->withErrors(array('Username already in use! Please choose a different username and try again.'))
                    ->withInput();
            }

            $apiuser = new ApiUser;
            $apiuser->active = $active;
            $apiuser->description = $description;
            $apiuser->username = $username;
            $apiuser->secret = $secret ? md5($secret) : '';
            $apiuser->callback_url = $callback_url;
            $apiuser->address = $address;
            $apiuser->created_by = App::make('authenticator')->getLoggedUser()->id;
            $apiuser->modified_by = App::make('authenticator')->getLoggedUser()->id;
            $apiuser->save();

            $id = $apiuser->id;
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
        $result = ApiUser::destroy($id);
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
        $apiUser = ApiUser::find($api_user_id);

        switch(Input::get('operation'))
        {
            //Delete
            case 0:
                $apiUser->data()->detach($data_id);
                $message = 'Permission deleted successfully';
                break;

            //Add
            case 1:
                $apiUser->data()->attach($data_id);
                $message = 'Permission added successfully';
                break;
        }

        return Redirect::route('apiuser.edit', array('id' => $api_user_id))->with('message', $message);
    }
}
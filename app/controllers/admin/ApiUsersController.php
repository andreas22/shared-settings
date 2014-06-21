<?php


class AdminApiUsersController extends BaseController {

    private $sidebar;

    public function __construct()
    {
        $count = ApiUser::all()->count();

        $this->sidebar = array(
            "API Users List <span class=\"badge\">$count</span>" => array('url' => URL::route('sharedsettings.apiuser.list'), 'icon' => '<i class="fa fa-users"></i>'),
            'Add New' => array('url' => URL::route('sharedsettings.apiuser.new'), 'icon' => '<i class="fa fa-plus-circle"></i>'),
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
        return View::make('admin.apiuser.index', array('apiuser' => $apiuser))->with('sidebar_items', $this->sidebar);
    }

    /**
     * View an API User
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id = 0)
    {
        $apiuser = ApiUser::find($id);
        return View::make('admin.apiuser.view', array('apiuser' => $apiuser))->with('sidebar_items', $this->sidebar);
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
        return View::make('admin.apiuser.edit', array('apiuser' => $apiuser))->with('sidebar_items', $this->sidebar);
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

        //'name', 'callback_url', 'address', 'modified_by', 'created_by'

        $id = Input::get('id');
        $description = Input::get('description');
        $callback_url = Input::get('callback_url');
        $address = Input::get('address');
        $username = Input::get('username');
        $secret = Input::get('secret');

        //Edit
        if($id)
        {
            $apiuser = ApiUser::find($id);
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

        return Redirect::route('sharedsettings.apiuser.edit', array('id' => $id))->with('message', "Successfully saved!");
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
        return Redirect::route('sharedsettings.apiuser.list')->with('message', $message);
    }
}
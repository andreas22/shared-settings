<?php

class AdminDataController extends BaseController {

    public function __construct()
    {

    }

    /**
     * Display a list of all settings
     *
     * @return mixed
     */
    public function index()
	{
        $settings = Data::all();
		return 'Data Page';
	}

    /**
     * Open a setting in edit view
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $setting = Setting::find($id);
        return View::make('settings.edit', array('setting' => $setting));
    }

    /**
     * Update a setting
     */
    public function save()
    {
        //Check if logged in user is allowed to save, move this checks on routes instead

        /*
         * 1. Delete current setting if id exists (keeping versioning)
         * 2. Save the new setting
         */
    }

    /**
     * Open a setting in read-only mode
     *
     * @param $id
     * @return mixed
     */
    public function read($id)
    {
        $setting = Setting::find($id);
        return View::make('settings.edit', array('setting' => $setting));
    }

    /**
     * Deletes a setting
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        //Check if logged in user is allowed to delete, move this checks on routes instead
        $result = Setting::destroy($id);
        $message = ($result) ? 'Deleted successfully' : 'Failed to delete';
        return View::make('settings.index')->with('message', $message);
    }
}
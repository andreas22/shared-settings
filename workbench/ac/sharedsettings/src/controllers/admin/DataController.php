<?php namespace Ac\Sharedsettings\Controllers\Admin;

use Ac\Sharedsettings\Models\Data;

class DataController extends Controller {

    private $sidebar;

    public function __construct()
    {
        $countData = Data::all()->count();

        $this->sidebar = array(
            "Data List <span class=\"badge\">$countData</span>" => array('url' => URL::route('sharedsettings.data.list'), 'icon' => '<i class="fa fa-list"></i>'),
            'Add New' => array('url' => URL::route('sharedsettings.data.new'), 'icon' => '<i class="fa fa-plus-circle"></i>'),
        );
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data = Data::paginate(15);
        return View::make('sharedsettings::admin.data.index', array('data' => $data))->with('sidebar_items', $this->sidebar);
	}

    /**
     * View a data
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id = 0)
    {
        $data = Data::find($id);
        if(strlen($data->content) == 0)
            $data->content = '{}';
        return View::make('sharedsettings::admin.data.view', array('data' => $data))->with('sidebar_items', $this->sidebar);
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id = 0)
	{
        $data = $id ? Data::find($id) : new Data();
        if(strlen($data->content) == 0)
            $data->content = '{}';
        return View::make('sharedsettings::admin.data.edit', array('data' => $data))->with('sidebar_items', $this->sidebar);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function save()
	{
        $validator = Validator::make(Input::all(), Data::$rules);

        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $id = Input::get('id');
        $title = Input::get('title');
        $description = Input::get('description');
        $content = Input::get('content');
        $code = Input::get('code');

        //Edit
        if($id)
        {
            $data = Data::find($id);

            if($data->code != $code)
            {
                return Redirect::back()
                    ->withErrors(array('Cheating is not a good idea!'))
                    ->withInput();
            }

            $data->title = $title;
            $data->description = $description;
            $data->content = $content;
            $data->modified_by = App::make('authenticator')->getLoggedUser()->id;
            $data->save();
        }
        //New
        else
        {
            $codeExists = Data::where('code', '=', $code)->count();
            if($codeExists)
            {
                return Redirect::back()
                    ->withErrors(array('Code is already in use!'))
                    ->withInput();
            }

            $data = new Data();
            $data->code = $code;
            $data->title = $title;
            $data->description = $description;
            $data->content = $content;
            $data->created_by = App::make('authenticator')->getLoggedUser()->id;
            $data->modified_by = App::make('authenticator')->getLoggedUser()->id;
            $data->save();
            $id = $data->id;
        }

        return Redirect::route('sharedsettings.data.edit', array('id' => $id))->with('message', "Successfully saved!");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
        $result = Data::destroy($id);
        $message = ($result) ? 'Deleted successfully' : 'Failed to delete';
        return Redirect::route('sharedsettings.data.list')->with('message', $message);
	}
}

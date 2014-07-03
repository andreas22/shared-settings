<?php namespace Ac\SharedSettings\Controllers\Admin;

use App;
use URL;
use Redirect;
use View;
use Input;
use Ac\SharedSettings\ViewModels\PaginateViewModel;
use Ac\SharedSettings\ViewModels\DataViewModel;
use Ac\SharedSettings\Repositories\DataRepositoryInterface;
use Ac\SharedSettings\Repositories\NotificationsRepositoryInterface;

class DataController extends \Controller {

    private $sidebar;

    private $code_prefix = 'DATA-';

    private $total_records;

    /*
     * Ac\SharedSettings\Repositories\DataRepositoryInterface
     */
    private $data;

    /*
     * @var Ac\SharedSettings\Repositories\NotificationsRepositoryInterface;
     */
    private $notifications;

    public function __construct(DataRepositoryInterface $data, NotificationsRepositoryInterface $notifications)
    {
        $this->data = $data;
        $this->notifications = $notifications;
        $this->total_records = $this->data->total();
        $this->sidebar = array(
            "Data List <span class=\"badge\">$this->total_records</span>" => array('url' => URL::route('data.list'), 'icon' => '<i class="fa fa-list"></i>'),
            'Add New' => array('url' => URL::route('data.new'), 'icon' => '<i class="fa fa-plus-circle"></i>'),
        );
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data = $this->data->all(15);
        $model = new PaginateViewModel();

        foreach($data as $d)
        {
            $m = new DataViewModel();
            $m->init($d);
            $model->add($m);
        }

        $model->links = $data->links();

        return View::make('sharedsettings::admin.data.index', array('model' => $model))
            ->with('sidebar_items', $this->sidebar);
	}

    /**
     * View a data
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id = 0)
    {
        $data = $this->data->find($id);
        $model = new DataViewModel();
        $model->init($data);
        return View::make('sharedsettings::admin.data.view', array('model' => $model))
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
        $data = $this->data->find($id);
        $model = new DataViewModel();
        $model->init($data);

        if($id)
        {
            $allowed_users = $this->data->getApiUsersAllowed($data->code);
            if($allowed_users)
                $model->hasPendingNotifications = $this->notifications->hasPendingNotification($id);
        }

        return View::make('sharedsettings::admin.data.edit', array('model' => $model))
            ->with('sidebar_items', $this->sidebar);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function save()
	{
        $validator = $this->data->validate(Input::all());

        if ($validator)
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();

        //Edit
        if(Input::has('id'))
        {
            $id = Input::get('id');
            $data = $this->data->save(Input::all());
            $created_by = App::make('authenticator')->getLoggedUser()->id;
            $allowed_users = $this->data->getApiUsersAllowed(Input::get('code'));

            if($allowed_users)
            {
                $notification_id = $this->notifications->create($id, $created_by, date('Y-m-d H:i:s'));

                if(Input::get('send_notification')){
                    $this->notifications->send($notification_id, $created_by);
                }
            }

            if($data == null)
                return Redirect::back()
                    ->withErrors(array('Cheating is not a good idea!'))
                    ->withInput();
        }
        //New
        else
        {
            $values = Input::all();
            $values['code'] = $this->code_prefix . date('YmdHi') . ($this->total_records + 1);
            $id = $this->data->create($values);
        }

        return Redirect::route('data.edit', array('id' => $id))->with('message', "Successfully saved!");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
        $result = $this->data->delete($id);
        $message = ($result) ? 'Deleted successfully' : 'Failed to delete';
        return Redirect::route('data.list')->with('message', $message);
	}
}

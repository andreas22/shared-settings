<?php

class AdminSharedSettingsController extends BaseController {

    public function __construct()
    {

    }

	public function getIndex()
	{
      return "Shared Setting";
	}

    public function getCreate()
    {
        Site::set('title', 'Add an API User');

        $form = Form::make(function ($form)
        {
            $form->with(new Application);
            $form->attributes([
                'url'    => handles('orchestra/foundation::resources/apiusers'),
                'method' => 'POST'
            ]);

            $form->fieldset(function ($fieldset)
            {
                $fieldset->control('input:text', 'name');
                $fieldset->control('input:text', 'callback_url');
                $fieldset->control('input:text', 'address');
            });
        });

        return View::make('admin.apiuser.edit', compact('form'));
    }

    public function postIndex()
    {
        $input = Input::all();
        $rules = array(
            'name' => ['required'],
            'callback_url'  => ['required'],
            'address'  => ['required']
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Redirect::to(handles('orchestra/foundation::resources/apiusers/create'))
                ->withInput()
                ->withErrors($validation);
        }

        $app = new ApiUser;
        $app->name = $input['name'];
        $app->callback_url = $input['callback_url'];
        $app->address = $input['address'];
        $app->created_by = Auth::user()->id;
        $app->modified_by = Auth::user()->id;
        $app->save();

        Messages::add('success', 'API User has been added');

        return Redirect::to(handles('orchestra/foundation::resources/apiusers'));
    }

    public function getEdit($id)
    {
        Site::set('title', 'Edit an API User');

        $form = Form::make(function ($form) use ($id)
        {
            $form->with(ApiUser::find($id));
            $form->attributes([
                'url'    => handles("orchestra/foundation::resources/apiusers/update/{$id}"),
                'method' => 'PUT'
            ]);

            $form->fieldset(function ($fieldset)
            {
                $fieldset->control('input:text', 'API User Name', 'name');
                $fieldset->control('input:text', 'Callback Url (On Change) ', 'callback_url');
                $fieldset->control('input:text', 'Allowed IP(s)', 'address');
            });
        });

        return View::make('admin.apiuser.edit', compact('form'));
    }

    public function putUpdate($id)
    {
        $app  = ApiUser::findOrFail($id);
        $input = Input::all();
        $rules = array(
            'name' => ['required'],
            'address'  => ['required']
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Redirect::to(handles("orchestra/foundation::resources/apiusers/edit/{$id}"))
                ->withInput()
                ->withErrors($validation);
        }

        $app->name = $input['name'];
        $app->callback_url = $input['callback_url'];
        $app->address = $input['address'];
        $app->modified_by = Auth::user()->id;
        $app->save();

        Messages::add('success', 'API User has been updated');

        return Redirect::to(handles('orchestra/foundation::resources/apiusers'));
    }

    public function getDelete($id)
    {
        $app = ApiUser::findOrFail($id);
        $app->delete();

        Messages::add('success', 'API User has been deleted');

        return Redirect::to(handles('orchestra/foundation::resources/apiusers'));
    }
}
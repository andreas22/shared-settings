@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
    Admin area | API User ({{ $apiuser->username ? $apiuser->username : 'New'}})
@stop

@section('content')
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> {{ $apiuser->id ? 'Edit' : 'Add new' }} </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8">
                <?php $message = Session::get('message'); ?>
                @if( isset($message) )
                <div class="alert alert-success">{{$message}}</div>
                @endif
                @if($errors->all() )
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif

                {{Form::open(array('route' => 'sharedsettings.apiuser.save', 'class' => 'well', 'id' => 'form')) }}
                    {{ Form::hidden('id', $apiuser->id) }}

                    <div class="form-group">
                        {{ Form::label('username', 'Username') }}
                        <div class="controls">
                            {{ Form::text('username', $apiuser->username, array('id' => 'username', 'style' => 'width: 100%', 'placeholder' => ' Username used by API to authenticate the user')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('secret', 'Secret') }}
                        <div class="controls">
                            <input type="password" name="secret" value="{{ $apiuser->secret }}" style="width: 100%" placeholder=" Secret used by API to authenticate the user">
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description') }}
                        <div class="controls">
                            {{ Form::text('description', $apiuser->description, array('id' => 'description', 'style' => 'width: 100%', 'placeholder' => ' A short description for the API user')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('callback_url', 'Callback Url') }}
                        <div class="controls">
                            {{ Form::text('callback_url', $apiuser->callback_url, array('id' => 'callback_url', 'style' => 'width: 100%', 'placeholder' => ' A callback url to be called upon data changes')) }}
                            <h6><small>Example: http://www.example.com/index.php</small></h6>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('address', 'Address') }}
                        <div class="controls">
                            {{ Form::text('address', $apiuser->address, array('id' => 'address', 'style' => 'width: 100%', 'placeholder' => ' The ip address that will access API')) }}
                            <h6><small>Example: 192.168.0.1 or 192.168.0.1,192.168.0.2 or 192.168.0.1-192.168.0.255</small></h6>
                        </div>
                    </div>

                    {{ Form::submit('Save', array('class'=>'btn btn-info', 'id' => 'save')) }}
                    <a href="{{ URL::route('sharedsettings.apiuser.list') }}" class="btn btn-info">Return</a>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop
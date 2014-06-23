@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
    Admin area: View API User
@stop

@section('content')
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> View  API User</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">

                    <div class="form-group">
                        {{ Form::label('username', 'Username') }}
                        <div class="controls">
                            {{ $apiuser->username }}
                        </div>
                    </div>

                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    <div class="controls">
                        {{ $apiuser->description }}
                    </div>
                </div>

                    <div class="form-group">
                        {{ Form::label('callback_url', 'Callback Url') }}
                        <div class="controls">
                            {{ $apiuser->callback_url }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('address', 'Address') }}
                        <div class="controls">
                            {{ $apiuser->address }}
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@stop
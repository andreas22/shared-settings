@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('head_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/jsoneditor/jsoneditor.min.css') }}">
    <script type="text/javascript" src="{{ asset('asset/jsoneditor/jsoneditor.min.js') }}"></script>
@stop

@section('title')
    Admin area: View Data
@stop

@section('content')
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> View Data </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">

                    <div class="form-group">
                        <div class="controls">
                            <div style="width: 100%; padding: 5px; background: none; color: #31708f; border: 1px solid #bce8f1 !important">
                                <i class="fa {{ $model->private ? 'fa-lock' : 'fa-unlock' }}"></i> {{ $model->code }}
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info public-url-info" style="display: {{ $model->private ? 'none' : 'block' }}" role="alert">
                        <h4><small>Public data can be accessed using the direct links below:</small></h4>
                        <i class="fa fa-external-link"></i>
                        <small><a href="{{  route('api.public.get', ['code' => $model->code]) }}" target="_blank">{{  route('api.public.get', ['code' => $model->code]) }}</a></small>
                        <div style="text-align: center"></div>
                        <i class="fa fa-external-link"></i>
                        <small><a href="{{  route('api.public.get', ['code' => $model->code, 'p' => 1]) }}" target="_blank">{{  route('api.public.get', ['code' => $model->code, 'p' => 1]) }}</a></small>
                    </div>

                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        <div class="controls">
                            <div style="width: 100%; padding: 5px; background: none; color: #31708f; border: 1px solid #bce8f1 !important">{{ $model->title }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description') }}
                        <div class="controls">
                            <div style="width: 100%; padding: 5px; background: none; color: #31708f; border: 1px solid #bce8f1 !important">{{ $model->description }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('type', 'Content Type') }}
                        <div class="controls">
                            <select id="type" name="type">
                                <option value="view">View</option>
                                <option value="text">Text</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('data', 'Content') }}
                        <div class="controls">
                            <div id="jsoneditor" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
<script type="text/javascript" >
    var options = {
        "mode": "view",
        "search": true
    };
    // create the editor
    var container = document.getElementById("jsoneditor");
    var editor = new JSONEditor(container, options);
    editor.setName('View');

    var json = {{ strlen($model->content) }} > 0 ? {{ $model->content }} : '';
    editor.set(json);

    $('#type').change(function(){
        var mode = $(this).val();
        editor.setMode(mode);
    });
</script>
@stop
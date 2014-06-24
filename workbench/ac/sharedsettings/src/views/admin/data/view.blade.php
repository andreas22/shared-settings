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
                        {{ Form::label('code', 'Code') }}
                        <div class="controls">
                            {{ $data->code }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        <div class="controls">
                            {{ $data->title }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description') }}
                        <div class="controls">
                            {{ $data->description }}
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

    var json = {{ strlen($data->content) }} > 0 ? {{ $data->content }} : '';
    editor.set(json);

    $('#type').change(function(){
        var mode = $(this).val();
        editor.setMode(mode);
    });
</script>
@stop
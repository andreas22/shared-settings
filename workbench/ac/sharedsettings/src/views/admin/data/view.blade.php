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
                        <small><a href="{{  route('api.public.get', ['code' => $model->code]) }}" target="_blank">Link 1</a></small>
                        <div style="text-align: center"></div>
                        <i class="fa fa-external-link"></i>
                        <small><a href="{{  route('api.public.get', ['code' => $model->code, 'suppress_response_codes' => 1]) }}" target="_blank">Link 2 (with suppress response codes)</a></small>
                        <div><h5><small>*Suppress response codes means it will return always http status 200 no matter what is the result.</small></h5></div>
                    </div>

                    <div style=" width: 100%; height:250px; display: {{ $model->private ? 'block' : 'none' }}" class="alert alert-info private-url-info">
                        <h4><small>Copy/paste the below code in an html file to access your private data.</small></h4>
                        <code>
                            <small>
                                &lt;form action="{{  route('api.public.get') }}" method="post"&gt;<br />
                                Code: &lt;input type="text" name="code" /&gt;<br />
                                Username: &lt;input type="text" name="username" /&gt;<br />
                                Password: &lt;input type="password" name="secret" /&gt;<br />
                                Suppress Response Codes: &lt;input type="text" name="suppress_response_codes" /&gt;<br />
                                &lt;input type="submit" name="Submit" /&gt;<br />
                                &lt;/form&gt;
                            </small>
                        </code>
                        <div><h5><small>*Suppress response codes means it will return always http status 200 no matter what is the result.</small></h5></div>
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
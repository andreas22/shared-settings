@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('head_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/jsoneditor/jsoneditor.min.css') }}">
    <script type="text/javascript" src="{{ asset('asset/jsoneditor/jsoneditor.min.js') }}"></script>
@stop

@section('title')
    Admin area: {{ $data->title ? $data->title : 'Data List'}}
@stop

@section('content')
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> {{ $data->id ? 'Edit' : 'Add new' }} </h3>
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

                {{Form::open(array('route' => 'data.save', 'class' => 'well', 'id' => 'form')) }}
                    {{ Form::hidden('id', $data->id) }}
                    {{ Form::hidden('content', '', array('id' => 'content')) }}

                    <div class="form-group" style="display: {{ $data->code == 'auto' ? 'none' : 'block'; }}">
                        <div class="controls">
                            {{ Form::text('code', $data->code, array('id' => 'code', 'style' => 'width: 100%; background: none; color: #6AA8B4; border: 1px solid #bce8f1 !important; text-align: center; border:0; font-size: 16px;', 'readonly' => 'readonly', 'placeholder' => ' A unique code to be used for api access')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls">
                            {{ Form::checkbox('private', '1', $data->private, ['id' => 'private']) }} {{ Form::label('private', 'Is Private?') }}
                            <h6>
                                <small>Setting your data as private it means that it will be only accessible through api user authentication</small>
                            </h6>
                        </div>
                    </div>

                    <div class="alert alert-info public-url-info" style="display: none" role="alert">
                        <h4><small>Public data can be accessed using the below link</small></h4>
                        <i class="fa fa-external-link"></i>
                        <small><a href="{{  route('api.public.get', ['code' => $data->code]) }}" target="_blank">{{  route('api.public.get', ['code' => $data->code]) }}</a></small>
                        <div style="text-align: center">or</div>
                        <i class="fa fa-external-link"></i>
                        <small><a href="{{  route('api.public.get', ['code' => $data->code, 'p' => 1]) }}" target="_blank">{{  route('api.public.get', ['code' => $data->code, 'p' => 1]) }}</a></small>
                    </div>

                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        <div class="controls">
                            {{ Form::text('title', $data->title, array('id' => 'title', 'style' => 'width: 100%', 'placeholder' => ' A clean and specific title')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description') }}
                        <div class="controls">
                            {{ Form::text('description', $data->description, array('id' => 'description', 'style' => 'width: 100%', 'placeholder' => ' A short description what is about')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('data', 'Content') }}
                        <div class="controls">
                            <div id="jsoneditor" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>

                    {{ Form::button('Save', array('class'=>'btn btn-info', 'id' => 'save')) }}
                    <a href="{{ URL::route('data.list') }}" class="btn btn-info">Return</a>
                {{ Form::close() }}
            </div>
            <div class="col-md-4">
                <h3 style="margin-top: 0;">Shortcut keys</h3>
                The editor supports shortcut keys for all available actions. The editor can be used by just a keyboard. The following short cut keys are available:
                <table class="table-bordered">
                    <thead>
                        <tr style="font-weight: bold">
                            <td>Key</td>
                            <td>Description</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Alt+Arrows</td>
                        <td>Move the caret up/down/left/right between fields</td>
                    </tr>
                    <tr>
                        <td>Ctrl+D</td>
                        <td>Duplicate field</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Del</td>
                        <td>Remove field</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Enter</td>
                        <td>Open link when on a field containing an url</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Ins</td>
                        <td>Insert a new field with type auto</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Shift+Ins</td>
                        <td>Append a new field with type auto</td>
                    </tr>
                    <tr>
                        <td>Ctrl+E</td>
                        <td>Expand or collapse field</td>
                    </tr>
                    <tr>
                        <td> Alt+End</td>
                        <td>Move the caret to the last field</td>
                    </tr>
                    <tr>
                        <td>Ctrl+F</td>
                        <td>Find</td>
                    </tr>
                    <tr>
                        <td>F3, Ctrl+G </td>
                        <td>Find next</td>
                    </tr>
                    <tr>
                        <td>Shift+F3, Ctrl+Shift+G</td>
                        <td>Find previous</td>
                    </tr>
                    <tr>
                        <td>Alt+Home</td>
                        <td>Move the caret to the first field</td>
                    </tr>
                    <tr>
                        <td>Ctrl+M</td>
                        <td>Show actions menu</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Z</td>
                        <td>Undo last action</td>
                    </tr>
                    <tr>
                        <td>Ctrl+Shift+Z</td>
                        <td>Redo</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer_scripts')
<script type="text/javascript" >
    var options = {
        "mode": "tree",
        "search": true
    };
    // create the editor
    var container = document.getElementById("jsoneditor");
    var editor = new JSONEditor(container, options);
    editor.setName('Test');

    var json = {{ strlen($data->content) }} > 0 ? {{ $data->content }} : '';
    editor.set(json);

    $(document).ready(function(){
        $('#save').click(function(){
            var json = editor.getText();
            $('#content').val(json);
            $('#form').submit();
        });

        $('.alert-success').delay(3000).slideUp('slow');

        start($('#private'));

        $('#private').change(function() {start(this);});

        function start($obj)
        {
            if ($($obj).is(":checked") || $('#code').val() == 'auto') {
                $(".public-url-info").slideUp('slow');
            } else {
                $(".public-url-info").slideDown('slow');
            }
        }
    });
</script>
@stop
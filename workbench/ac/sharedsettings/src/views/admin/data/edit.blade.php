@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('head_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/jsoneditor/jsoneditor.min.css') }}">
    <script type="text/javascript" src="{{ asset('asset/jsoneditor/jsoneditor.min.js') }}"></script>
@stop

@section('title')
    Admin area: {{ $model->title ?: 'Data List'}}
@stop

@section('content')
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> {{ $model->id ? 'Edit' : 'Add new' }} </h3>
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
                    {{ Form::hidden('id', $model->id) }}
                    {{ Form::hidden('content', '', array('id' => 'content')) }}
                    {{ Form::hidden('send_notification', 0, array('id' => 'send_notification')) }}

                    {{ Form::hidden('code', $model->code, array('id' => 'code')) }}

                    <div class="form-group">
                        <div class="controls">
                            {{ Form::checkbox('private', '1', $model->private, ['id' => 'private']) }} {{ Form::label('private', 'Is Private?') }}
                            <h6>
                                <small>Setting your data as private it means that it will be only accessible through api user authentication</small>
                            </h6>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        <div class="controls">
                            {{ Form::text('title', $model->title, array('id' => 'title', 'style' => 'width: 100%', 'placeholder' => ' A clean and specific title')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description') }}
                        <div class="controls">
                            {{ Form::text('description', $model->description, array('id' => 'description', 'style' => 'width: 100%', 'placeholder' => ' A short description what is about')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('data', 'Content') }}
                        <div class="controls">
                            <div id="jsoneditor" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>

                    {{ Form::button('Save', array('class'=>'btn btn-info', 'id' => 'save')) }}
                    @if(!empty($model->id) && $model->apiusers->count() > 0)
                        {{ Form::button('Save & Notify', array('class'=>'btn btn-warning', 'id' => 'notify')) }}
                    @endif
                    <a href="{{ URL::route('data.list') }}" class="btn btn-info">Return</a>
                {{ Form::close() }}
            </div>
            <div class="col-md-4">

                <div class="form-group" style="display: {{ $model->code == 'auto' ? 'none' : 'block'; }}">
                    <div class="controls">
                        {{ Form::text('code1', $model->code, array('style' => 'width: 100%; background: none; color: #31708f; border: 1px solid #bce8f1 !important; text-align: center; border:0; font-size: 16px;', 'readonly' => 'readonly', 'placeholder' => ' A unique code to be used for api access')) }}
                    </div>
                </div>

                <div class="alert alert-warning" style="display: {{ $model->hasPendingNotifications ? 'block' : 'none'}}" role="alert">
                    <i class="fa fa-warning"></i> API Users have not been notify since {{ $model->hasPendingNotifications }}. Click 'Save & Notify' if you wish to let them know.
                </div>

                <div class="alert alert-info public-url-info" style="display: none" role="alert">
                    <h4><small>Public data can be accessed using the direct links below:</small></h4>
                    <i class="fa fa-external-link"></i>
                    <small><a href="{{  route('api.public.get', ['code' => $model->code]) }}" target="_blank">Link 1</a></small>
                    <div style="text-align: center"></div>
                    <i class="fa fa-external-link"></i>
                    <small><a href="{{  route('api.public.get', ['code' => $model->code, 'suppress_response_codes' => 1]) }}" target="_blank">Link 2 (with suppress response codes)</a></small>
                    <div><h5><small>*Suppress response codes means it will return always http status 200 no matter what is the result.</small></h5></div>
                </div>

                <div style="overflow-x: scroll; width: 100%; height:290px; display: none" class="alert alert-info private-url-info">
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

                <br />

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

    var json = {{ $model->content }};
    editor.set(json);

    $(document).ready(function(){
        $('#save').click(function(){
            var json = editor.getText();
            $('#content').val(json);
            $('#form').submit();
        });

        $('#notify').click(function(){
            var json = editor.getText();
            $('#content').val(json);
            $('#send_notification').val(1);
            $('#form').submit();
        });

        $('.alert-success').delay(3000).slideUp('slow');

        start($('#private'));

        $('#private').change(function() {start(this);});

        function start($obj)
        {
            if ($($obj).is(":checked") || $('#code').val() == 'auto') {
                $(".public-url-info").slideUp('slow');
                $(".private-url-info").slideDown('slow');
            } else {
                $(".public-url-info").slideDown('slow');
                $(".private-url-info").slideUp('slow');
            }
        }
    });
</script>
@stop
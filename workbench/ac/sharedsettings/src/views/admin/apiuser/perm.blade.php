{{-- add permission --}}
@if($perm_model->available_permissions)
    {{Form::open(["route" => "apiuser.permissions.save","role"=>"form", 'class' => 'form-add-perm'])}}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon form-button button-add-perm"><span class="glyphicon glyphicon-plus-sign add-input"></span></span>
                {{Form::select('data_id', $perm_model->available_permissions, '', ["class"=>"form-control permission-select"])}}
            </div>
            <span class="text-danger">{{$errors->first('permissions')}}</span>
            {{Form::hidden('api_user_id', $perm_model->api_user_id)}}
            {{-- add permission operation --}}
            {{Form::hidden('operation', 1)}}
        </div>
        @if(empty($perm_model->api_user_id))
            <div class="form-group">
                <span class="text-danger"><h5>You need to create an API User first and then assign any permissions.</span>
            </div>
        @endif
    {{Form::close()}}
@endif

{{-- remove permission --}}
@if( sizeof($perm_model->api_user_permissions) > 0 )
    @foreach($perm_model->api_user_permissions as $permission)
        {{Form::open(["route" => "apiuser.permissions.save", "role"=>"form", 'class' => 'form-del-perm', 'id' => 'frm' . $permission->id])}}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon form-button button-del-perm" ss-perm-id="{{ $permission->id }}">
                    <span class="glyphicon glyphicon-minus-sign add-input"></span>
                </span>
                {{Form::text('permission_desc', sprintf('[%s] %s', $permission->code, $permission->title), ['class' => 'form-control', 'readonly' => 'readonly'])}}
                {{Form::hidden('api_user_id', $perm_model->api_user_id)}}
                {{Form::hidden('data_id', $permission->id)}}
                {{-- add permission operation --}}
                {{Form::hidden('operation', 0)}}
            </div>
        </div>
        {{Form::close()}}
    @endforeach
@elseif(!empty($perm_model->api_user_id))
    <span class="text-warning"><h5>There are no permissions associated with this API user.</h5></span>
@endif

@section('footer_scripts')
@parent
<script>
    $(".button-add-perm").click( function(){
        <?php if(!empty($perm_model->api_user_id)): ?>
        $('.form-add-perm').submit();
        <?php endif; ?>
    });
    $(".button-del-perm").click( function(){
        var permission_id = $(this).attr('ss-perm-id');
        $('#frm'+permission_id).submit();
    });
</script>
@stop
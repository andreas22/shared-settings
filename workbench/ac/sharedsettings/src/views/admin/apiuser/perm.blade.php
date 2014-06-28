{{-- add permission --}}
@if($permission_values)
    {{Form::open(["route" => "apiuser.permissions.save","role"=>"form", 'class' => 'form-add-perm'])}}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon form-button button-add-perm"><span class="glyphicon glyphicon-plus-sign add-input"></span></span>
                {{Form::select('data_id', $permission_values, '', ["class"=>"form-control permission-select"])}}
            </div>
            <span class="text-danger">{{$errors->first('permissions')}}</span>
            {{Form::hidden('api_user_id', $apiuser->id)}}
            {{-- add permission operation --}}
            {{Form::hidden('operation', 1)}}
        </div>
        @if(! $apiuser->exists)
            <div class="form-group">
                <span class="text-danger"><h5>You need to create an API User first and then assign any permissions.</span>
            </div>
        @endif
    {{Form::close()}}
@endif

{{-- remove permission --}}
@if( sizeof($user_acl) > 0 )
    @foreach($user_acl as $permission)
        {{Form::open(["route" => "apiuser.permissions.save", "role"=>"form", 'class' => 'form-del-perm', 'id' => 'frm' . $permission->id])}}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon form-button button-del-perm" ss-perm-id="{{ $permission->id }}">
                    <span class="glyphicon glyphicon-minus-sign add-input"></span>
                </span>
                {{Form::text('permission_desc', sprintf('[%s] %s', $permission->code, $permission->title), ['class' => 'form-control', 'readonly' => 'readonly'])}}
                {{Form::hidden('api_user_id', $apiuser->id)}}
                {{Form::hidden('data_id', $permission->id)}}
                {{-- add permission operation --}}
                {{Form::hidden('operation', 0)}}
            </div>
        </div>
        {{Form::close()}}
    @endforeach
@elseif($apiuser->exists)
    <span class="text-warning"><h5>There are no permissions associated with this API user.</h5></span>
@endif

@section('footer_scripts')
@parent
<script>
    $(".button-add-perm").click( function(){
        <?php if($apiuser->exists): ?>
        $('.form-add-perm').submit();
        <?php endif; ?>
    });
    $(".button-del-perm").click( function(){
        var permission_id = $(this).attr('ss-perm-id');
        $('#frm'+permission_id).submit();
    });
</script>
@stop
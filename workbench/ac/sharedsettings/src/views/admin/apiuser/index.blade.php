@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
    Admin area: API Users List
@stop

@section('content')
<div class="row">
    {{-- data lists --}}
    @include('sharedsettings::admin.apiuser.apiuser-table')
</div>
<script type="text/javascript" >
    $(document).ready(function(){
        $('.alert-success').delay(3000).slideUp('slow');
    });
</script>
@stop

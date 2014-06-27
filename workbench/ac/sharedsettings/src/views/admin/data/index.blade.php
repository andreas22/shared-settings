@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
    Admin area: Data List
@stop

@section('content')
<div class="row">
    {{-- data lists --}}
    @include('sharedsettings::admin.data.data-table')
</div>
@stop

@section('footer_scripts')
<script type="text/javascript" >
    $(document).ready(function(){
        $('.alert-success').delay(3000).slideUp('slow');
    });
</script>
@stop



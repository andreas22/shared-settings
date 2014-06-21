@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
    Admin area: API Users List
@stop

@section('content')
<div class="row">
    {{-- data lists --}}
    @include('admin.apiuser.apiuser-table')
</div>
@stop

@section('footer_scripts')

@stop



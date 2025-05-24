@extends('apps::dashboard.layouts.app')
@section('title', __('apps::dashboard.app_homes.routes.index'))
@section('content')

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="#">{{__('apps::dashboard.app_homes.routes.index')}}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    {!! Menu::render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
    {!! Menu::scripts() !!}
@stop

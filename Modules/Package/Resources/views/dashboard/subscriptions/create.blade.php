@extends('apps::dashboard.layouts.app')
@section('title', __('package::dashboard.packages.routes.create'))
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.index.title') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{ url(route('dashboard.packages.index')) }}">
                        {{__('package::dashboard.packages.routes.index')}}
                    </a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="#">{{__('package::dashboard.packages.routes.create')}}</a>
                </li>
            </ul>
        </div>

        <h1 class="page-title"></h1>

        <div class="row">
            {!! Form::model($model,[
                           'url'=> route('dashboard.packages.store'),
                           'id'=>'form',
                           'role'=>'form',
                           'method'=>'POST',
                           'class'=>'form-horizontal form-row-seperated',
                           'files' => true
                           ])!!}
                <div class="col-md-12">

                    {{-- RIGHT SIDE --}}
                    <div class="col-md-3">
                        <div class="panel-group accordion scrollable" id="accordion2">
                            <div class="panel panel-default">

                                <div id="collapse_2_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <ul class="nav nav-pills nav-stacked">
                                            <li class="active">
                                                <a href="#general" data-toggle="tab">
                                                    {{ __('package::dashboard.packages.form.tabs.general') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PAGE CONTENT --}}
                    <div class="col-md-9">
                        <div class="tab-content">

                            {{-- CREATE FORM --}}
                            <div class="tab-pane active fade in" id="general">
                                <div class="col-md-10">

                                    @include('package::dashboard.packages.form')

                                </div>
                            </div>

                            {{-- PAGE ACTION --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @include('apps::dashboard.layouts._ajax-msg')
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-lg blue">
                                            {{__('apps::dashboard.buttons.add')}}
                                        </button>
                                        <a href="{{url(route('dashboard.packages.index')) }}" class="btn btn-lg red">
                                            {{__('apps::dashboard.buttons.back')}}
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            {!! Form::close()!!}
        </div>
    </div>
</div>
@stop

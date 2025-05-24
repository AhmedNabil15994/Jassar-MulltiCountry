@extends('apps::dashboard.layouts.app')
@section('title', __('package::dashboard.print-settings.routes.update'))
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
                        <a href="{{ url(route('dashboard.print-settings.index')) }}">
                            {{__('package::dashboard.print-settings.routes.index')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('package::dashboard.print-settings.routes.update')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.print-settings.update',$model->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">

                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('package::dashboard.print-settings.form.tabs.general') }}
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

                                {{-- UPDATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    {{--                                    <h3 class="page-title">{{__('package::dashboard.print-settings.form.tabs.general')}}</h3>--}}

                                     {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                {{-- CREATE FORM --}}

                                <div class="tab-pane active fade in" id="global_setting">


                                
                                    <div class="tab-content px-1 pt-1">

                                        

                                        <div class="col-md-10">

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.name')}}
                                                  
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" max="255" name="name"
                                                           class="form-control"
                                                           value="{{$model->name}}"
                                                           data-name="name">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.description')}}
                                                  
                                                </label>
                                                <div class="col-md-9">
                                                    <textarea  name="description"
                                                           class="form-control"
                                                           data-name="description">{{$model->description}}</textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.is_continuous')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="is_continuous"
                                                           data-size="small"
                                                           value="1"
                                                           {{($model->is_continuous == 1) ? ' checked="" ' : ''}}
                                                           name="is_continuous">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.top_margin')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="top_margin"
                                                           
                                                           value="{{$model->top_margin}}"
                                                           name="top_margin">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.left_margin')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="left_margin"
                                                           
                                                           value="{{$model->left_margin}}"
                                                           name="left_margin">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.width')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="width"
                                                           
                                                           value="{{$model->width}}"
                                                           name="width">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.height')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="height"
                                                           
                                                           value="{{$model->height}}"
                                                           name="height">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.paper_width')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="paper_width"
                                                           min="0.1"
                                                           value="{{$model->paper_width}}"
                                                           name="paper_width">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.paper_height')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="paper_height"
                                                           min="0.1"
                                                           value="{{$model->paper_height}}"
                                                           name="paper_height">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.stickers_in_one_row')}} 
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="stickers_in_one_row"
                                                           min="1"
                                                           value="{{$model->stickers_in_one_row}}"
                                                           name="stickers_in_one_row">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.row_distance')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="row_distance"
                                                           
                                                           value="{{$model->row_distance}}"
                                                           name="row_distance">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.col_distance')}} in
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="col_distance"
                                                           min="0.0"
                                                           value="{{$model->col_distance}}"
                                                           name="col_distance">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.stickers_in_one_sheet')}} 
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text"
                                                           class="form-control"
                                                           data-name="stickers_in_one_sheet"
                                                           min="1"
                                                          
                                                           name="stickers_in_one_sheet">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                           

                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('package::dashboard.print-settings.form.status')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small"
                                                           value="1"
                                                           {{($model->status == 1) ? ' checked="" ' : ''}}
                                                           name="status">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- END CREATE FORM --}}
                            </div>
                        </div>

                                </div>

                                {{-- END UPDATE FORM --}}

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{__('apps::dashboard.buttons.edit')}}
                                    </button>
                                    <a href="{{url(route('dashboard.print-settings.index')) }}" class="btn btn-lg red">
                                        {{__('apps::dashboard.buttons.back')}}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script></script>

@endsection

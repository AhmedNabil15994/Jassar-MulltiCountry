@extends('apps::dashboard.install.layout',[
    'completed' => ['general','country','logo'],
    'current' => 'complete',
])

@section('content')
    <h2>4. @lang('Complete')</h2>

    <div class="box">
        <div class="installation-message text-center">
            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            <h3>@lang('installed successfully')!</h3>
        </div>

        <div class="clearfix"></div>

        <div class="visit-wrapper text-center clearfix">
            <div class="row">
                <div class="col-sm-4">
                    <a href="{{ route('frontend.home') }}" class="visit text-center" target="_blank">
                        <div class="icon">
                            <i class="fa fa-desktop" aria-hidden="true"></i>
                        </div>

                        <h5>@lang('Go to Your Website')</h5>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="{{ route('dashboard.home') }}" class="visit text-center" target="_blank">
                        <div class="icon">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </div>

                        <h5>@lang('Admin dashboard')</h5>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="{{ route('dashboard.setting.index') }}" class="visit text-center" target="_blank">
                        <div class="icon">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </div>

                        <h5>@lang('Show More Settings')</h5>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

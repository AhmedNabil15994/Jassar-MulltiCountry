@extends('apps::dashboard.install.layout',[
    'completed' => ['general','country'],
    'current' => 'logo',
])

@section('content')
<h2>3.
    @lang('Logos')
</h2>

{!! Form::open([
    'url'=> route('dashboard.install.save-logos-setup'),
    'id'=>'form',
    'role'=>'form',
    'method'=>'POST',
    'class'=>'form-horizontal form-row-seperated',
    'files' => true
    ])!!}
<div class="box">

    @include('core::dashboard.shared.file_upload', [
        'name' => 'images[logo]',
        'label' => 'logo',
        'imgUploadPreviewID' => 'settingLogo',
        'image' => setting('images.logo') ?? null,
    ])

    @include('core::dashboard.shared.file_upload', [
        'name' => 'images[white_logo]',
        'label' => 'white_logo',
        'imgUploadPreviewID' => 'settingWhiteLogo',
        'image' => setting('images.white_logo') ?? null,
    ])

    @include('core::dashboard.shared.file_upload', [
        'name' => 'images[favicon]',
        'label' => 'favicon',
        'imgUploadPreviewID' => 'settingFavicon',
        'image' => setting('images.favicon') ?? null,
    ])
    <div class="table-responsive">
        <table class="table">

        </table>
    </div>
   
</div>

@include('apps::dashboard.layouts._ajax-msg')
<div class="content-buttons clearfix">
    <button type="submit" id="submit" class="btn btn-primary pull-right">
        Continue
    </button>
</div>
{!! Form::close()!!}

@endsection

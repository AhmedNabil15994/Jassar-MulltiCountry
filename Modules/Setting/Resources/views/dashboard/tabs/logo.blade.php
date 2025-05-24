<div class="tab-pane fade" id="logo">

    {{-- <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.logo') }}</h3> --}}

    <div class="col-md-10">

        {{-- <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.logo') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="logo" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[logo]" class="form-control logo" type="text" readonly
                           value="{{ setting('images.logo') ? url(setting('images.logo')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img src="{{ setting('images.logo') ? url(setting('images.logo')) : ''}}" style="height: 15rem;">
                </span>
            </div>
        </div> --}}

        @include('core::dashboard.shared.file_upload', [
            'name' => 'images[logo]',
            'imgUploadPreviewID' => 'settingLogo',
            'image' => setting('images.logo') ?? null,
        ])

        @include('core::dashboard.shared.file_upload', [
            'name' => 'images[white_logo]',
            'imgUploadPreviewID' => 'settingWhiteLogo',
            'image' => setting('images.white_logo') ?? null,
        ])

        @include('core::dashboard.shared.file_upload', [
            'name' => 'images[favicon]',
            'imgUploadPreviewID' => 'settingFavicon',
            'image' => setting('images.favicon') ?? null,
        ])

    </div>
</div>

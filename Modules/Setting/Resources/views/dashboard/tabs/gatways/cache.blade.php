
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.supported_countries') }}
            </label>
            <div class="col-md-9">
                <select name="payment_gateway[cache][supported_countries]" class="form-control select2" multiple="" data-placeholder="{{ __('setting::dashboard.settings.form.all_countries') }}">
                    @foreach ($countries as $code => $country)
                        <option value="{{ $code }}"
                                @if (collect(setting('payment_gateway.cache.supported_countries',[]))->contains($code))
                                selected=""
                            @endif>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @foreach (array_keys(config('laravellocalization.supportedLocales')) as $code)

            {!! field()->text('payment_gateway[cache][title_'.$code.']', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_title').'-'.$code ,
            Setting::get('payment_gateway.cache.title_'.$code)) !!}

        @endforeach
        {!! field()->checkBox('payment_gateway[cache][status]', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_status') , null , [
        (Setting::get('payment_gateway.cache.status') == 'on' ? 'checked' : '') => ''
        ]) !!}
        @include('core::dashboard.shared.file_upload', [
            'name' => 'images[cache_logo]',
            'imgUploadPreviewID' => 'settingFavicon',
            'image' => Setting::get('cache_logo') ?? null,
        ])
    </div>
</div>
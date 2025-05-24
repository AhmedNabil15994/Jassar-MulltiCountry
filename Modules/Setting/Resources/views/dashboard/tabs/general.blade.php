<div class="tab-pane active fade in" id="global_setting">
    <div class="col-md-10">
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.locales') }}
            </label>
            <div class="col-md-9">
                <select name="locales[]" class="form-control select2" multiple="">
                    @foreach (config('core.available-locales') as $key => $language)
                        <option value="{{ $key }}"
                                @if (in_array($key,array_keys(config('laravellocalization.supportedLocales'))))
                                selected
                            @endif>
                            {{ $language['native'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.rtl_locales') }}
            </label>
            <div class="col-md-9">
                <select name="rtl_locales[]" class="form-control select2" multiple="">
                    @foreach (config('core.available-locales') as $key => $language)
                        <option value="{{ $key }}"
                                @if (in_array($key,Setting::get('rtl_locales') ?? []))
                                selected
                            @endif>
                            {{ $language['native'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.default_language') }}
            </label>
            <div class="col-md-9">
                <select name="default_locale" class="form-control select2">
                    @foreach (config('core.available-locales',[]) as $key => $language)
                        <option value="{{ $key }}"
                                @if (Setting::get('default_locale') == $key)
                                selected
                            @endif>
                            {{ $language['native'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $default_country = Setting::get('default_country') ;
        @endphp
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.default_country') }}
            </label>
            <div class="col-md-9">
                <select name="default_country" class="form-control select2">
                    <option> Select Value</option>
                    @foreach ($countries as $code => $country)
                        <option value="{{ $code }}"
                                {{$default_country == $code ? 'selected' : ''}}
                        >
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.supported_countries') }}
            </label>
            <div class="col-md-9">
                <select name="supported_countries[]" class="form-control select2" multiple="">
                    @foreach ($countries as $code => $country)
                        <option value="{{ $code }}"
                                @if (collect(setting('supported_countries'))->contains($code))
                                selected=""
                            @endif>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @php
            $currencies = \Modules\Area\Entities\CurrencyCode::whereIn('country_id', Setting::get('supported_countries'))->get();
        @endphp
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.default_currency') }}
            </label>
            <div class="col-md-9">
                <select name="default_currency" class="form-control select2">
                    @foreach ($currencies as $code => $currency)
                        <option value="{{ $currency->code }}" {{$currency->code == setting('default_currency') ? 'selected' : ''}}>
                            {{ $currency->translate('name','ar') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.supported_currencies') }}
            </label>
            <div class="col-md-9">
                <select name="supported_currencies[]" class="form-control select2" multiple="">
                    @foreach ($currencies as $code => $currency)
                        <option value="{{ $currency->code }}"
                                @if (in_array($currency->code,setting('supported_currencies') ?? []))
                                selected=""
                            @endif>
                            {{ $currency->translate('name','ar') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

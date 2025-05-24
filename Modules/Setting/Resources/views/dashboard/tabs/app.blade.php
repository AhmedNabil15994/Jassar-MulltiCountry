<div class="tab-pane fade" id="app">
    {{-- <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.app') }}</h3> --}}
    <div class="col-md-10">

        {{-- tab for lang --}}
        <ul class="nav nav-tabs">
            @foreach (array_keys(config('laravellocalization.supportedLocales')) as $code)
                <li class="@if ($loop->first) active @endif">
                    <a data-toggle="tab"
                        href="#first_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
                </li>
            @endforeach
        </ul>

        {{-- tab for content --}}
        <div class="tab-content">

            @foreach (array_keys(config('laravellocalization.supportedLocales')) as $code)
                <div id="first_{{ $code }}"
                    class="tab-pane fade @if ($loop->first) in active @endif">

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.app_name') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="app_name[{{ $code }}]"
                                value="{{ setting('app_name.' . $code) }}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.app_description') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="3" name="app_description[{{ $code }}]">{{ setting('app_description.' . $code) }}</textarea>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>


        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_email') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[email]"
                    value="{{ setting('contact_us.email') ? setting('contact_us.email') : '' }}" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_whatsapp') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[whatsapp]" placeholder=""
                    value="{{ setting('contact_us.whatsapp') ? setting('contact_us.whatsapp') : '' }}" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_mobile') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[mobile]"
                    value="{{ setting('contact_us.mobile') ? setting('contact_us.mobile') : '' }}" />
            </div>
        </div>

    </div>
</div>

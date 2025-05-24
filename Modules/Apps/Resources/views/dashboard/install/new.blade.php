@extends('apps::dashboard.install.layout')

@section('content')
<h2>1.
    {{ __('setting::dashboard.settings.form.tabs.app') }}
</h2>

{!! Form::open([
    'url'=> route('dashboard.install.save-general-info'),
    'id'=>'form',
    'role'=>'form',
    'method'=>'POST',
    'class'=>'form-horizontal form-row-seperated',
    'files' => true
    ])!!}
<div class="box">
    <div class="table-responsive">
        <table class="table">

            <tbody>
                @foreach (array_keys(config('laravellocalization.supportedLocales')) as $code)
                    
                    <tr>
                        <td>
                            {{ __('setting::dashboard.settings.form.app_name') }} - {{ $code }}
                        </td>

                        <td class="text-center">
                                    <input type="text" class="form-control" name="app_name[{{ $code }}]" data-name="app_name.{{ $code }}"
                                        value="{{ setting('app_name.' . $code) }}" />
                        </td>
                    </tr>
                @endforeach
                
                    
                <tr>
                    <td>
                        {{ __('setting::dashboard.settings.form.contacts_email') }}
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control" name="contact_us[email]" data-name="contact_us.email"
                            value="{{ setting('contact_us.email') ? setting('contact_us.email') : '' }}" />
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        {{ __('setting::dashboard.settings.form.contacts_whatsapp') }}
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control" name="contact_us[whatsapp]" placeholder="" data-name="contact_us.whatsapp"
                            value="{{ setting('contact_us.whatsapp') ? setting('contact_us.whatsapp') : '' }}" />
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        {{ __('setting::dashboard.settings.form.contacts_mobile') }}
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control" name="contact_us[mobile]" data-name="contact_us.mobile"
                            value="{{ setting('contact_us.mobile') ? setting('contact_us.mobile') : '' }}" />
                    </td>
                </tr>
            </tbody>
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

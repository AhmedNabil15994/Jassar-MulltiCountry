@extends('apps::dashboard.install.layout',[
    'completed' => ['general'],
    'current' => 'country',
])

@section('content')
<h2>2.
    @lang('Country settings')
</h2>

@inject('countries' ,'Modules\Area\Entities\Country')
@php
    $countries = $countries->pluck('title','id')->toArray();
    $default_country = Setting::get('default_country') ;
@endphp
{!! Form::open([
    'url'=> route('dashboard.install.save-country-setup'),
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
                    
                <tr>
                    <td>
                        {{ __('setting::dashboard.settings.form.default_country') }}
                    </td>

                    <td class="text-center">
                        <select name="default_country" class="form-control select2" data-name="">
                            <option> Select Value</option>
                            @foreach ($countries as $code => $country)
                                <option value="{{ $code }}"
                                        {{$default_country == $code ? 'selected' : ''}}
                                >
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        {{ __('setting::dashboard.settings.form.default_currency') }}
                    </td>
                    <td class="text-center">
                        <select name="default_currency" class="form-control select2">
                            <option> Select Value</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->code }}"
                                        {{$default_currency == $currency->code ? 'selected' : ''}}>
                                    {{ $currency->translate('name','ar') }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
   
</div>

@include('apps::dashboard.layouts._ajax-msg',['alert' => ['class' => 'alert-warning','dec' => __('This takes some time, please wait')]])
<div class="content-buttons clearfix">
    <button type="submit" id="submit" class="btn btn-primary pull-right">
        Continue
    </button>
</div>
{!! Form::close()!!}

@endsection

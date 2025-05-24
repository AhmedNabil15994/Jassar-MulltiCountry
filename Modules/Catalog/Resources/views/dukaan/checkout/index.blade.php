@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@push('styles')
    <style>
        .hidden{
            display: none !important;
        }
    </style>
@endpush
@section('content')
@php
    $lastAddress = auth()->check() ? auth()->user()->addresses()->with('state')->orderBy('id','DESC')->first() : (
        session()->has('address_id') ? \Modules\User\Entities\Address::find(session()->get('address_id')) : null
    );
@endphp

<div class="inner-page">
    <div class="container">
        <div class="row cart-page justify-content-md-center">
            <div class="col-md-8">
                <div class="checkout-block">
                    <div class="head">
                        <h3>{{__('catalog::frontend.checkout.invoice_details')}}<i class="ti-id-badge"></i></h3>
                    </div>
                </div>
                <form method="post" action="{{URL::current()}}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <input type="text" v-model="user.name" class="form-control" name="username" placeholder="{{__('catalog::frontend.checkout.name')}}" required>
                            <input type="hidden" name="addressType" value="local">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email"  v-model="user.email" class="form-control" name="email" placeholder="{{__('catalog::frontend.checkout.email')}}">
                        </div>
                        <div class="form-group col-md-6">
                            @php
                                $lastCountryId = $lastAddress != null ? $lastAddress->state->country_id : COUNTRY_ID;
                            @endphp
                            <div class='phone-cont'>
                                <select class="selectpicker select-country" name="phone_code" data-live-search="true" v-model="user.calling_code">
                                    @foreach($countries as $country)
                                        <option value="{{$country->phone_code}}" data-content="{!!"{$country->emoji} <span class='country-name'>{$country->title}</span> <span class='code'>+{$country->phone_code}</span>"!!}  "></option>
                                    @endforeach
                                </select>
                                <input
                                    :class="'form-control ' +  (errors.hasOwnProperty('phone_code') || errors.hasOwnProperty('mobile')
                                    ? 'is-invalid' : (success_submit_update_profile && !errors.hasOwnProperty('phone_code') == true && !errors.hasOwnProperty('mobile') == true ? ' is-valid' : ''))"
                                    placeholder="@lang('front.Phone No')"
                                    type="tel"
                                    name="mobile"
                                    v-model="user.mobile"
                                />
                            </div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('phone_code')">@{{errors.phone_code[0]}}</div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('mobile')">@{{errors.mobile[0]}}</div>
                        </div>

                        <div class="form-group col-md-6 hidden">
                            <select class="select2 form-control" name="country_id" id="country_id_to_select">
                                <option value='0'>{{__('catalog::frontend.checkout.country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{$country->id == ($lastAddress != null ? $lastAddress->state->country_id : COUNTRY_ID) ? 'selected' : ''}}>{{$country->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <select class="select2  form-control" name="state_id"></select>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" name="address" value="{{$lastAddress != null ? $lastAddress->address : old('address')}}" placeholder="{{__('catalog::frontend.checkout.addressTitle')}}" required>
                        </div>
{{--                        <div class="form-group col-md-6">--}}
{{--                            <input type="text" class="form-control" name="block" value="{{$lastAddress != null ? $lastAddress->block : old('block')}}" placeholder="{{__('catalog::frontend.address.form.block')}}" >--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <input type="text" class="form-control" name="building" value="{{$lastAddress != null ? $lastAddress->building : old('building')}}" placeholder="{{__('catalog::frontend.address.form.building')}}" >--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <input type="text" class="form-control" name="floor" value="{{$lastAddress != null ? $lastAddress->floor : old('floor')}}" placeholder="{{__('catalog::frontend.address.form.floor')}}" >--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <input type="text" class="form-control" name="flat" value="{{$lastAddress != null ? $lastAddress->flat : old('flat')}}" placeholder="{{__('catalog::frontend.address.form.flat')}}" >--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <textarea class="form-control" name="note" placeholder="{{__('catalog::frontend.checkout.note')}}">{{$lastAddress != null ? $lastAddress->note : old('note')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn theme-btn d-block">{{__('catalog::frontend.checkout.next')}}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('vuejs')

    <script>
        let user = {name:''};
        @if(count($errors))
            @foreach($errors->all() as $error)
                toastr['error']("{{$error}}")
            @endforeach
        @endif

        @auth
        user = @json(auth()->user())
        @else
            @if($lastAddress != null)
            user = {
                name: "{{$lastAddress->username}}",
                calling_code: "{{$lastAddress->phone_code}}",
                email: "{{$lastAddress->email}}",
                mobile: "{{$lastAddress->mobile}}",
            }
            @endif
        @endauth

        function VueData(data){

            data.user = user;
            data.errors = [];
            data.updateBtnLoader = false;
            data.success_submit_update_profile = false;

            data.current_password = '';
            data.new_password = '';
            data.new_password_confirmation = '';

            data.passwordUpdateBtnLoader = false;
            data.password_success_submit_update_profile = false;

            return data;
        }
    </script>
    <script>
        $(function (){
            $('select[name="country_id"]').on('change',function (){
                let val = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: "{{route('frontend.checkout.getCities',['countryId'=>':val','countryPrefix'=>request()->segment(2)])}}".replace(":val",val),
                    success: function (data){
                        let x = '';
                        let lang = "{{request()->segment(1)}}";
                        $('select[name="state_id"]').empty().select2('destroy');
                        $.each(data.data,function (index,item){
                            x+= '<optgroup label="'+item.title[lang]+'" data-select2-id="'+item.id+'">';
                            $.each(item.states,function (stateIndex,stateItem){
                                x+= '<option value="'+stateItem.id+'" data-select2-id="'+stateItem.id+'">'+stateItem.title[lang]+'</option>';
                            });
                            x+= '</optgroup>';
                        });
                        $('select[name="state_id"]').append(x).select2();
                    }
                })
            });

            $('select[name="phone_code"]').val("{{\Modules\Area\Entities\Country::find($lastCountryId)->phone_code}}");
            $('select[name="phone_code"]').selectpicker('refresh')

            let countryId = "{{$lastAddress != null ? $lastAddress->state->country_id : COUNTRY_ID}}";
            if(countryId){
                $('select[name="country_id"]').val(countryId).trigger('change')
            }
        })

    </script>
@endsection

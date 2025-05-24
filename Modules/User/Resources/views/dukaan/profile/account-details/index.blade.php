@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class='row'>
            <div class="col-md-3">
                <x-dukaan-user-menu/>
            </div>
            <div class="col-md-9">
                <form action="" class="contact-form">
                    <h3 class="inner-title">@lang('front.Account Details')</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class='form-label'>@lang('front.Name') <span class="text-danger">*</span></label>
                            <input :class="'form-control ' +  (errors.hasOwnProperty('name') ? 'is-invalid' : '') + (success_submit_update_profile && !errors.hasOwnProperty('name') == true ? ' is-valid' : '')"
                            type="text" placeholder="" v-model="user.name">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('name')">@{{errors.name[0]}}</div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class='form-label'>@lang('front.Email') <span class="text-danger">*</span></label>
                            <input  :class="'form-control ' +  (errors.hasOwnProperty('email') ? 'is-invalid' : '') + (success_submit_update_profile && !errors.hasOwnProperty('email') == true ? ' is-valid' : '')"
                             type="email" placeholder="" v-model="user.email">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('email')">@{{errors.email[0]}}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class='form-label'>@lang('front.Phone')</label>
                            <div class='phone-cont'>
                                <select class="selectpicker select-country" data-live-search="true" v-model="user.calling_code">
                                    @foreach($countries as $country)
                                        <option value="{{$country->phone_code}}"  data-content="{!!"{$country->emoji} <span class='country-name'>{$country->title}</span> <span class='code'>+{$country->phone_code}</span>"!!}  "></option>
                                    @endforeach
                                </select>
                                <input
                                    :class="'form-control ' +  (errors.hasOwnProperty('phone_code') || errors.hasOwnProperty('mobile')
                                    ? 'is-invalid' : (success_submit_update_profile && !errors.hasOwnProperty('phone_code') == true && !errors.hasOwnProperty('mobile') == true ? ' is-valid' : ''))"
                                    placeholder="@lang('front.Phone No')."
                                    type="text"
                                    v-model="user.mobile"
                                />
                            </div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('phone_code')">@{{errors.phone_code[0]}}</div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('mobile')">@{{errors.mobile[0]}}</div>
                        </div>
{{--                        <div class="col-md-6 form-group">--}}
{{--                            <label class='form-label'>@lang('front.State') <span class="text-danger">*</span></label>--}}
{{--                            <select class="select2 form-control" id="city_id" v-model="user.city_id">--}}

{{--                                @foreach($countries as $country)--}}
{{--                                    <optgroup label="{{$country->title}}" data-select2-id="{{$country->id}}">--}}
{{--                                        @foreach($country->cities as $city)--}}
{{--                                            <option value="{{$city->id}}" data-select2-id="{{$city->id}}">{{$city->title}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </optgroup>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('city_id')">@{{errors.city_id[0]}}</div>--}}
{{--                        </div>--}}
                    </div>
                    <div class="d-flex justify-content-end">
                        <button @click="updateProfile()" class="btn theme-btn" type="button" :disabled="updateBtnLoader">
                            <span v-if="updateBtnLoader"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Save Changes')</span>
                        </button>
                    </div>
                </form>
                <form action="" class="contact-form">
                    <h3 class="inner-title">@lang('front.Change Password')</h3>
                    <div class="form-group">
                        <label class='form-label'>@lang('front.Current Password') <span class="text-danger">*</span></label>
                        <input
                            :class="'form-control ' +  (errors.hasOwnProperty('current_password') ? 'is-invalid' : '') + (password_success_submit_update_profile && !errors.hasOwnProperty('current_password') == true ? ' is-valid' : '')"
                            v-model="current_password" type="password" placeholder="">
                        <div class="invalid-feedback invalid-feedback-active"
                            v-if="errors.hasOwnProperty('current_password')">
                            @{{errors.current_password[0]}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='form-label'>@lang('front.New Password') <span class="text-danger">*</span></label>
                        <input
                            :class="'form-control ' +  (errors.hasOwnProperty('password') ? 'is-invalid' : '') + (password_success_submit_update_profile && !errors.hasOwnProperty('password') == true ? ' is-valid' : '')"
                            v-model="new_password" type="password" placeholder="">
                        <div class="invalid-feedback invalid-feedback-active"
                            v-if="errors.hasOwnProperty('password')">
                            @{{errors.password[0]}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='form-label'>@lang('front.Confirm Password') <span class="text-danger">*</span></label>
                        <input class="form-control" v-model="new_password_confirmation" type="password" placeholder="">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button @click="updatePassword()" class="btn theme-btn" type="button" :disabled="passwordUpdateBtnLoader">

                            <span v-if="passwordUpdateBtnLoader"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Update Password')</span>

                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('vuejs')

<script>

    let user = @json(auth()->user());

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

    function VueMethods(methods){

        methods.updateProfile = function(){
            this.updateBtnLoader = true;
            this.errors = [];
            axios.post("{{route('frontend.profile.update')}}",{
                phone_code: this.user.calling_code,
                mobile: this.user.mobile,
                city_id: $("#city_id").val(),
                email: this.user.email,
                name: this.user.name,
            }).then(response => {
                this.success_submit_update_profile = true;
                alertMessage('success', response.data.message);
                this.updateBtnLoader = false;
                this.errors = [];
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.updateBtnLoader = false;
                this.success_submit_update_profile = true;
                $.each(error.response.data.errors,function (index,item){
                    for (let i = 0; i < item.length; i++) {
                        alertMessage('error', item[i]);
                    }
                });
                // alertMessage('error', error.response.data.message);
            });
        }

        methods.updatePassword = function(){
            this.passwordUpdateBtnLoader = true;
            this.errors = [];
            axios.post("{{route('frontend.profile.update.password')}}",{
                current_password: this.current_password,
                password: this.new_password,
                password_confirmation: this.new_password_confirmation,
            }).then(response => {
                alertMessage('success', response.data.message);
                this.passwordUpdateBtnLoader = false;
                this.errors = [];
                this.password_success_submit_update_profile = true;
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.passwordUpdateBtnLoader = false;
                this.password_success_submit_update_profile = true;
                alertMessage('error', error.response.data.message);
            });
        }

        return methods;
    }
</script>

@endsection

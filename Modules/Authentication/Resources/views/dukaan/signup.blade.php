@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="login">
                    <h2>@lang('front.Welcome')</h2>
                    <p>@lang('front.Fill out the form to get started').</p>
                    <form class="login-form" method="post" novalidate="true" @submit="checkForm">
                        <div class="form-group"> 
                             <input type="text" v-model="username"
                                :class="'form-control ' +  (errors.hasOwnProperty('name') ? 'is-invalid' : '') +
                                    (success_submit && !errors.hasOwnProperty('name') == true ? ' is-valid' : '')" 
                                placeholder="Username">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('name')">@{{errors.name[0]}}</div>
                
                        </div>
                        <div class="form-group">
                            <div class='phone-cont'>
                                <select class="selectpicker select-country" data-live-search="true" v-model="phone_code">
                                    @foreach($countries as $country)
                                        <option value="{{$country->phone_code}}" id="{{$country->id}}"  data-content="{!!"{$country->emoji} <span class='country-name'>{$country->title}</span> <span class='code'>+{$country->phone_code}</span>"!!}  "></option>
                                    @endforeach
                                </select>
                                <input  
                                    :class="'form-control ' +  (errors.hasOwnProperty('phone_code') || errors.hasOwnProperty('mobile') 
                                    ? 'is-invalid' : (success_submit && !errors.hasOwnProperty('phone_code') == true && !errors.hasOwnProperty('mobile') == true ? ' is-valid' : ''))" 
                                    placeholder="Phone No." 
                                    type="text"  
                                    v-model="mobile"
                                />
                            </div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('phone_code')">@{{errors.phone_code[0]}}</div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('mobile')">@{{errors.mobile[0]}}</div>
                        </div>
                        <div class="form-group">  
                            <input type="email" v-model="email"
                                :class="'form-control ' +  (errors.hasOwnProperty('email') ? 'is-invalid' : '') +
                                    (success_submit && !errors.hasOwnProperty('email') == true ? ' is-valid' : '')" 
                                placeholder="Email">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('email')">@{{errors.email[0]}}</div>
                        
                        </div>
                        <div class="form-group position-relative">
                            <i class="position-absolute fas eye-slash" id="showPass"></i>
                            <input type="password" id="passInput" 
                            :class="'form-control ' +  
                            (errors.hasOwnProperty('password') ? 'is-invalid' : '') + 
                            (success_submit && !errors.hasOwnProperty('password') == true ? ' is-valid' : '')"
                             name="password" placeholder="Password" v-model="password">
                             <div class="invalid-feedback invalid-feedback-active"
                              v-if="errors.hasOwnProperty('password')">@{{errors.password[0]}}</div>
                        </div>
                        <div class="form-group position-relative">
                            <i class="position-absolute fas eye-slash" id="showPass2"></i>
                            <input type="password" id="showPass2" 
                            class="form-control"
                             name="password" placeholder="Confirm Password" v-model="password_confirmation">
                        </div>
                        <button type="submit" class="btn theme-btn btn-block form-group" type="button" :disabled="submit_btn">
                        
                            <span v-if="submit_btn"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Create an account')</span>

                        </button>
                    </form>

                    <div class="mt-40 text-center">
                        <span class="text-muted d-block mb-10">@lang('front.Already have an account?')</span>
                        <a class="btn theme-btn-sec btn-block" href="{{route('frontend.login')}}">@lang('front.Login In')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('vuejs')

<script>

    function VueData(data){

        data.errors = [];
        data.countries = @json($countries);
        data.submit_btn = false;
        data.success_submit= false;
        
        data.username = '';
        data.phone_code = data.countries.find(country => country.id == parseInt('{{currentCountry()}}')).phone_code;
        data.mobile = '';
        data.email = '';
        data.password = '';
        data.password_confirmation = '';

        return data;
    }

    function vueCreated(){
        console.log(this.countries)
    }

    function VueMethods(methods){

        methods.checkForm = function(e){
            e.preventDefault();
            this.submit_btn = true;
            this.errors = [];

            axios.post("{{route('frontend.post.register')}}",{
                name: this.username,
                phone_code: this.phone_code,
                mobile: this.mobile,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }).then(response => {
                this.errors = [];
                redirectToUrl('{{route("frontend.home")}}');

            }).catch(error => {

                this.errors = error.response.data.errors;
                this.submit_btn = false;
                this.success_submit = true;
                alertMessage('error', error.response.data.message);
            });
        }

        return methods;
    }
</script>

@endsection
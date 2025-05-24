@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="login">
                    <h2>@lang('front.Welcome') <b>@lang('front.Back')</b></h2>
                    <p>@lang('front.Login to manage your account').</p>
                    <form class="login-form" method="post" novalidate="true" @submit="checkForm">
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
                        <div class="form-group d-flex align-items-center justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault50" v-model="remember_me">
                                <label class="form-check-label check-note" for="flexCheckDefault50">
                                  @lang('front.Remember me')
                                </label>
                            </div>
                            <a class="link-muted" href="{{route('frontend.password.request')}}">@lang('front.Lost your password?')</a>
                        </div> 
                        <button type="submit" class="btn theme-btn btn-block form-group" type="button" :disabled="submit_btn">
                        
                            <span v-if="submit_btn"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Login Now')</span>

                        </button>
                    </form>

                    <div class="mt-40 text-center">
                        <span class="text-muted d-block mb-10">@lang('front.Don\'t have an account?')</span>
                        <a class="btn theme-btn-sec btn-block" href="{{route('frontend.register')}}">@lang('front.Sign Up')</a>
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
        data.submit_btn = false;
        data.success_submit= false;

        data.email = '';
        data.password = '';
        data.remember = 0;

        return data;
    }

    function VueMethods(methods){

        methods.checkForm = function(e){
            e.preventDefault();
            this.submit_btn = true;
            this.errors = [];

            axios.post("{{route('frontend.post_login')}}",{
                email: this.email,
                password: this.password,
                remember_me: this.remember_me,
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
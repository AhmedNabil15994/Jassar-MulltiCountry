@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="login">
                    <h2>@lang('front.Forgot Your') <b>@lang('front.Password')</b></h2>
                    <p>@lang('front.Enter your email address below and we\'ll get you back on track').</p>
                    <form class="login-form" method="post" novalidate="true" @submit="checkForm">
                        <div class="form-group">
                            <input type="email" v-model="email"
                                :class="'form-control ' +  (errors.hasOwnProperty('email') ? 'is-invalid' : '') +
                                (success_submit && !errors.hasOwnProperty('email') == true ? ' is-valid' : '')" 
                            placeholder="Email">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('email')">@{{errors.email[0]}}</div>
                   
                        </div>
                        <button type="submit" class="btn theme-btn btn-block form-group" type="button" :disabled="submit_btn">
                        
                            <span v-if="submit_btn"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Request Reset Link')</span>

                        </button>
                    </form>

                    <div class="mt-40 text-center">
                        <span class="text-muted d-block">@lang('front.Have a trouble in your account?') 
                            <a href="index.php?page=contact-us">@lang('front.Contact Us')</a></span>
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

        return data;
    }

    function VueMethods(methods){

        methods.checkForm = function(e){
            e.preventDefault();
            this.submit_btn = true;
            this.errors = [];

            axios.post("{{route('frontend.password.email')}}",{
                email: this.email,
            }).then(response => {
                
                this.submit_btn = false;
                alertMessage('success', response.data.message);
            
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
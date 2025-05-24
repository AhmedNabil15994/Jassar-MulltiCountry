@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="login">
                    <h2>@lang('front.Reset Your') <b>@lang('front.Password')</b></h2>
                    <p>@lang('front.Your email address is') @{{email}}.</p>
                    <form class="login-form" method="post" @submit="checkForm" novalidate="true">
                        <div class="form-group">
                            <label class='form-label'>@lang('front.New Password') <span class="text-danger">*</span></label>
                            <input 
                                :class="'form-control ' +  (errors.hasOwnProperty('password') ? 'is-invalid' : '') + (password_success_submit_update_profile && !errors.hasOwnProperty('password') == true ? ' is-valid' : '')"
                                v-model="password" type="password" placeholder="">
                            <div class="invalid-feedback invalid-feedback-active" 
                                v-if="errors.hasOwnProperty('password')">
                                @{{errors.password[0]}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='form-label'>@lang('front.Confirm Password') <span class="text-danger">*</span></label>
                            <input class="form-control" v-model="password_confirmation" type="password" placeholder="">
                        </div>
                        <button type="submit" class="btn theme-btn btn-block form-group" type="button" :disabled="submit_btn">
                        
                            <span v-if="submit_btn"><x-dukaan-btn-loader/></span>
                            <span v-else class="btn-text">@lang('front.Change password')</span>

                        </button>
                    </form>

                    <div class="mt-40 text-center">
                        <span class="text-muted d-block">@lang('front.Have a trouble in your account?') <a href="index.php?page=contact-us">@lang('front.Contact Us')</a></span>
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

        data.email = '{{$email}}';
        data.password = '';
        data.password_confirmation = '';
        data.remember = 0;

        return data;
    }

    function VueMethods(methods){

        methods.checkForm = function(e){
            e.preventDefault();
            this.submit_btn = true;
            this.errors = [];

            axios.post("{{route('frontend.password.update')}}",{
                token: '{{$token}}',
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }).then(response => {
                
                alertMessage('success', response.data.message);
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
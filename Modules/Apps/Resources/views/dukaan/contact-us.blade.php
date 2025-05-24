@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="contact-page">
            <div class="section-block">
                <div class="section-title text-center">
                    <h2><span>@lang('front.Keep in touch with us')</span></h2>
                    <p class="sub-title-p">@lang('front.We’re talking about clean beauty gift sets, of course – and we’ve got a bouquet of beauties for yourself or someone you love').</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <a class="contact-block text-center d-block" href="mailto:{{setting('contact_us.email','')}}">
                            <img class="img-fluid" src='/dukaan/images/icons/email-2.svg' alt=''/>
                            <h3>@lang('front.Email')</h3>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="contact-block text-center d-block"  href="tel:{{setting('contact_us.mobile','')}}">
                            <img class="img-fluid" src='/dukaan/images/icons/phone.svg' alt=''/>
                            <h3>@lang('front.Phone')</h3>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="contact-block text-center d-block" href="https://wa.me/+{{setting('contact_us.whatsapp','')}}?text=How can we help?">
                            <img class="img-fluid" src='/dukaan/images/icons/whatsapp.svg' alt=''/>
                            <h3>@lang('front.Whatsapp')</h3>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9 col-12 contact-form">
                    <div class="section-title text-center">
                        <h2><span>@lang('front.Send Message')</span></h2>
                        <p class="sub-title-p">@lang('front.We’re talking about clean beauty gift sets, of course – and we’ve got a bouquet of beauties for yourself or someone you love').</p>
                    </div>
                    <form @submit="checkForm">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-label">@lang('front.Full Name') <span class="text-danger">*</span></label>
                                <input v-model="username"
                                    :class="'form-control ' +  (errors.hasOwnProperty('username') ? 'is-invalid' : '') +
                                    (success_submit && !errors.hasOwnProperty('username') == true ? ' is-valid' : '')" 
                                 type="text" placeholder="First Name">
                                 <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('username')">@{{errors.username[0]}}</div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">@lang('front.Email') <span class="text-danger">*</span></label>
                                <input v-model="email"
                                    :class="'form-control ' +  (errors.hasOwnProperty('email') ? 'is-invalid' : '') +
                                    (success_submit && !errors.hasOwnProperty('email') == true ? ' is-valid' : '')" 
                                 type="email" placeholder="Email">
                                 <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('email')">@{{errors.email[0]}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="form-label">@lang('front.Phone')</label>
                                <input v-model="mobile"
                                    :class="'form-control ' +  (errors.hasOwnProperty('mobile') ? 'is-invalid' : '') +
                                    (success_submit && !errors.hasOwnProperty('mobile') == true ? ' is-valid' : '')" 
                                 type="text" placeholder="Mobile">
                                 <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('mobile')">@{{errors.mobile[0]}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('front.Message') <span class="text-danger">*</span></label>
                            <textarea v-model="message"
                                :class="'form-control ' +  (errors.hasOwnProperty('message') ? 'is-invalid' : '') +
                                (success_submit && !errors.hasOwnProperty('message') == true ? ' is-valid' : '')" 
                             placeholder="Message"></textarea>
                             <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('message')">@{{errors.message[0]}}</div>
                        </div>
                        <div class="text-center">

                            <button type="submit" class="btn theme-btn btn-block form-group" type="button" :disabled="submit_btn">
                            
                                <span v-if="submit_btn"><x-dukaan-btn-loader/></span>
                                <span v-else class="btn-text">@lang('front.Send Message')</span>

                            </button>
                            <p class="text-muted">@lang('front.We\'ll get back to you in 1-2 business days').</p>
                        </div>
                    </form>
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

        data.username = '';
        data.email = '';
        data.mobile = '';
        data.message = '';

        return data;
    }

    function VueMethods(methods){

        methods.checkForm = function(e){
            e.preventDefault();
            this.submit_btn = true;
            this.errors = [];

            axios.post("{{route('frontend.send-contact-us')}}",{
                username: this.username,
                email: this.email,
                mobile: this.mobile,
                message: this.message,
            }).then(response => {
                this.errors = [];
                this.username = '';
                this.email = '';
                this.mobile = '';
                this.message = '';
                alertMessage('success', '{{__('apps::frontend.contact_us.alerts.send_message')}}');
                this.submit_btn = false;

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

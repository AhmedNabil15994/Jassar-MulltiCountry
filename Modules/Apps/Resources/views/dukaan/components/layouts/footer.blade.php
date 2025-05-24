<div class="subscribtion-sec">
    <div class="container">
        <div class="subscribe-form">
            <h2>{{__('apps::frontend.subscribe.title')}}</h2>
            <form>
                <div class="d-flex align-items-center flex-1">
                    <img class="img-fluid" src='/images/icons/email-2.svg' alt=''/>
                    <input type="email" placeholder="{{__('apps::frontend.subscribe.type_email')}}" />
                </div>
                <button class='btn'>{{__('apps::frontend.subscribe.btn')}} <i class='ti-angle-right'></i></button>
            </form>
        </div>
    </div>
</div>

<footer>
    <div class="bg-shape">
        <img src="/images/shapes/left-shape.png" data-parallax='{"x": -130}' alt="shape" class="shape-left">
        <img src="/images/shapes/right_shape.png" data-parallax='{"x": 130}' alt="shape" class="shape-right">
    </div>
    <div class="container">
        <div class="footer-top">
            <div class="app-download">
                <div class="app-desc wow fadeInUp">
                    <h2>{{setting('app_name.'.locale())}}</h2>
                    <div class=" d-flex justify-content-center flex-wrap">

                        <div class="left-side">
                            <a class="header-logo" href="{{route('frontend.home')}}"><img class="img-fluid" src="{{setting('logo')}}" alt="" /></a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="footer-help">
                <div class="wow fadeInUp">
                    <a class="d-flex align-items-center contact-phone" href="tel:{{setting('contact_us.mobile')}}">
                        <img class="icon-img img-fluid" src="/dukaan/images/icons/phone-call.svg" alt="icon" />
                        {{setting('contact_us.mobile')}}
                        </a>
                </div>
                <div class="footer-contact wow fadeInUp">
                    <h4>{{__('apps::frontend.subscribe.follow_us')}}</h4>
                    <div class="footer-social">
                        @if(setting('setting.social.facebook') && setting('setting.social.facebook') != '#')
                            <a href="{{setting('setting.social.facebook')}}"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(setting('setting.social.instagram') && setting('setting.social.instagram') != '#')
                            <a href="{{setting('setting.social.instagram')}}"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(setting('setting.social.youtube') && setting('setting.social.youtube') != '#')
                            <a href="{{setting('setting.social.linkedin')}}"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(setting('setting.social.twitter') && setting('setting.social.twitter') != '#')
                            <a href="{{setting('setting.social.twitter')}}"><i class="fab fa-twitter"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="https://wa.me/{{setting('contact_us.whatsapp')}}?text=How can we help?" target="_blank" class="whatsappbtn">
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets2.lottiefiles.com/private_files/lf30_vfaddvqs.json"  background="transparent"  speed="1" loop autoplay></lottie-player>
    </a>
</footer>
<p class="copyrights mb-0 wow fadeIn">
    {{__('apps::frontend.subscribe.footer_rights')}} <a href="trydukan.com">Dukaan</a>
</p>
        <div class="space"></div>
        <div class="menu-modal side-modal">
            <div class="side-modal-head">
                <a class="header-lang" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL((locale() == 'en' ?'ar' : 'en'), null, [], true) }}">
                    {{locale() == 'en' ? 'العربية' : 'English'}}
                </a>
                <a class="logo" href='{{route('frontend.home')}}'><img class="img-fluid" src="{{setting('logo')}}" alt="" /></a>
                <button class="btn close-modal"><i class="ti-close"></i></button>
            </div>
            <div class="side-modal-content">
                <div class="help-sec">
                    <div class="d-flex align-items-center justify-content-between">
                        <a  href="tel:{{setting('contact_us.mobile')}}">
                            <img class="img-fluid" src="/images/icons/phone.svg" alt="" />
                            Call Us
                        </a>
                        <a href="https://wa.me/{{setting('contact_us.whatsapp')}}?text=How can we help?">
                            <img class="img-fluid" src="/images/icons/whatsapp.svg" alt="" />
                            Whatsapp
                        </a>
                        <a href="mailto:{{setting('contact_us.email')}}">
                            <img class="img-fluid" src="/images/icons/email-2.svg" alt="" />
                            Email
                        </a>
                    </div>
                    <div class="menu-social d-flex align-items-center justify-content-center">
                        @if(setting('setting.social.facebook') && setting('setting.social.facebook') != '#')
                            <a href="{{setting('setting.social.facebook')}}"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(setting('setting.social.instagram') && setting('setting.social.instagram') != '#')
                            <a href="{{setting('setting.social.instagram')}}"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(setting('setting.social.youtube') && setting('setting.social.youtube') != '#')
                            <a href="{{setting('setting.social.linkedin')}}"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(setting('setting.social.twitter') && setting('setting.social.twitter') != '#')
                            <a href="{{setting('setting.social.twitter')}}"><i class="fab fa-twitter"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="countriesModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="countriesModelTitle"> أختر الدولة </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
            </div>
            <div class="modal-body">
                <form method="get" action="{{URL::current()}}">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <select class="selectpicker select-country" name="country_id" data-live-search="true" @change="changeCountry($event)">

                                @foreach($supported_countries as $country)
                                    <option  value="{{$country->id}}" data-content="{!!"{$country->emoji} {$country->title}"!!}"></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn theme-btn" type="submit">تأكيد الدولة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

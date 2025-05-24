
<!--Start Header Area-->
<div class="body-overlay" id="body-overlay"></div>
<header>
    <div class="top-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="left-side">
{{--                    <button class="side-menu"><i class="ti-help-alt"></i> <span class="btn-text">@lang('front.Help')</span></button>--}}
                </div>
                <div class="right-side">
                    <a class="header-lang" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL((locale() == 'en' ?'ar' : 'en'), null, [], true) }}">
                        {{locale() == 'en' ? 'العربية' : 'English'}}
                    </a>
{{--                    <select class="selectpicker select-currency" data-live-search="true"  @change="changeCurrency($event)">--}}
{{--                        @foreach($supported_currencies as $currency)--}}
{{--                        <option vlaue="{{$currency}}" {{$current_currency == $currency ? 'selected' : ''}}>{{$currency}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    <select class="selectpicker select-country" data-live-search="true" @change="changeCountry($event)">

                        @foreach($supported_countries as $country)
                            <option  {{COUNTRY_ID == $country->id ? 'selected' : ''}} value="{{$country->id}}" data-content="{!!"{$country->emoji} {$country->title}"!!}"></option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="middle-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="left-side">
                    <a class="header-logo" href="{{route('frontend.home')}}"><img class="img-fluid" src="{{setting('logo')}}" alt="" /></a>
                </div>
                <div class="right-side">

                    <x-dukaan-header-search/>

                    @auth()
                        <a class="header-btn" href="{{route('frontend.profile.index')}}">
                            <img class="img-fluid icon-img" src="/dukaan/images/icons/user.svg" alt=""/>
                            <span class="btn-text">{{auth()->user()->name}}</span>
                        </a>

                        <a class="wishlit-btn header-btn" href="{{route('frontend.profile.favourites.index')}}">
                            <span class="shopping-desc">
                                <span class="shopping-price"><i class="counter" v-if="favourateProducts.length">@{{favourateProducts.length}}</i></span>
                                <img class="img-fluid icon-img" src="/dukaan/images/icons/heart.svg" alt="" />
                            </span>
                            <span class="btn-text"> @lang('front.Wishlist')</span>
                        </a>
                    @else
                        <a class="header-btn" href="{{route('frontend.login')}}">
                            <img class="img-fluid icon-img" src="/dukaan/images/icons/login.png" alt=""/>
                            <span class="btn-text">@lang('front.Login')</span>
                        </a>
                    @endauth

                    <x-dukaan-header-cart/>
                </div>
            </div>
        </div>
    </div>

    <x-dukaan-header-menu/>
</header>

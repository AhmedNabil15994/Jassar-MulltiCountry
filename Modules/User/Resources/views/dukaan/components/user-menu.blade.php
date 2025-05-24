
<div class='user-menu'>
    <a class="{{ url()->current() == route('frontend.profile.index') ? 'active' : '' }}" href="{{route('frontend.profile.index')}}">
        <img class="img-fluid icon-img" src="/dukaan/images/icons/user.svg" alt=""/>
        <span>{{ __('user::frontend.profile.index.title') }}</span>
    </a>

    <a class="{{ url()->current() == route('frontend.profile.favourites.index') ? 'active' : '' }}" href="{{route('frontend.profile.favourites.index')}}">
        <img class="img-fluid icon-img" src="/dukaan/images/icons/heart.svg" alt="">
        <span> {{ __('user::frontend.profile.index.favourites') }}</span>
    </a>

{{--    <a class="{{ url()->current() == route('frontend.profile.address.index') ? 'active' : '' }}" href="{{ route('frontend.profile.address.index') }}">--}}
{{--        <img class="img-fluid icon-img" src="/dukaan/images/icons/location.svg" alt=""/>  --}}
{{--        <span>{{ __('user::frontend.profile.index.addresses') }}</span>--}}
{{--    </a>--}}

    <a class="{{ url()->current() == route('frontend.orders.index') ? 'active' : '' }}" href="{{ route('frontend.orders.index') }}">
        <img class="img-fluid icon-img" src="/dukaan/images/icons/bag-2.svg" alt=""/>
        <span>{{ __('user::frontend.profile.index.my_orders') }}</span>
    </a>

    <a onclick="event.preventDefault();document.getElementById('logout').submit();"
    href="javascript:;">
        <img class="img-fluid icon-img" src="/dukaan/images/icons/logout.png" alt=""/>
        <span> {{ __('user::frontend.profile.index.logout') }}</span>
    </a>
    <form id="logout" action="{{ route('frontend.logout') }}" method="POST">
        @csrf
    </form>
</div>

<div class="bottom-header">
    <div class="container">
        <button class="menu-responsive"><i class="ti-menu"></i> @lang('front.Menu')</button>
        <div class="slide-menu-static display-mobile-block">
            <div class="right-side">
                <a class="header-lang" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL((locale() == 'en' ?'ar' : 'en'), null, [], true) }}">
                    {{locale() == 'en' ? 'العربية' : 'English'}}
                </a>
                <a class='header-lang c-flags' data-bs-toggle="modal" data-bs-target="#countriesModel">
                    <img class='flag' src='/dukaan/images/flags/kw.svg'> {{__('apps::frontend.subscribe.kw')}}
                </a>
            </div>
        </div>
        <div class="main-menu">
            <button class="btn close-modal"><i class="ti-close"></i> @lang('front.Close')</button>
            <ul>
                @foreach($header_links as $link)
                    @if(count($link['children']))

                        <li class="nsted-menu">
                            <a href="#">{{$link['title']}}</a>

                            <ul class="submenu dropdown-menu">
                                <x-dukaan-header-nsted-menu :link="$link"/>
                            </ul>
                        </li>

                    @else
                        <x-dukaan-header-final-builder :link="$link"/>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '--') || {{ config('app.name') }} </title>
    <meta name="description" content="">
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ setting('favicon') ? setting('favicon') : asset('frontend/favicon.png') }}"/>

    <x-dukaan-site-colors/>

    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/fontawesome.min.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/themify-icons.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/animate.min.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/select2.min.css" type="text/css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/bootstrap-select.min.css" type="text/css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/jquery.mCustomScrollbar.css" type="text/css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/smoothproducts.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css" type="text/css">

    @stack('style_plugins')

    {{-- Start - Bind Css Code From Dashboard Daynamic --}}
    {!! setting('custom_codes.css_in_head') ?? null !!}
    {{-- End - Bind Css Code From Dashboard Daynamic --}}

    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/style-{{locale()}}.css">
    <link rel="stylesheet" href="/dukaan/{{locale()}}/css/custom-{{locale()}}.css">

    {{-- Start - Bind Js Code From Dashboard Daynamic --}}
    {!! setting('custom_codes.js_before_head') ?? null !!}
    {{-- End - Bind Js Code From Dashboard Daynamic --}}
    @stack('styles')
    <style>
        .offer_product{
            cursor: pointer;
        }
        @if(locale() == 'ar')
        .pull-left{
            float: right;
        }
        @else
        .pull-left{
            float: left;
        }
        @endif
    </style>
    <style>
        @media(max-width: 767px){
            .menu-responsive{
                @if(locale()=='ar')
                float: left;
                @else
                float: right;
                @endif
                margin-top: 8px;
            }
            .slide-menu-static .right-side{
                @if(locale()=='ar')
                float: right;
                text-align: right;
                @else
                float: left;
                text-align: left;
                @endif
                display: block;
                width: 100%;
            }
            .slide-menu-static .header-lang,
            .slide-menu-static a.header-lang.c-flags{
                display: inline-block;
            }
            .sp-large a{
                height: auto;
            }
        }
    </style>

</head>


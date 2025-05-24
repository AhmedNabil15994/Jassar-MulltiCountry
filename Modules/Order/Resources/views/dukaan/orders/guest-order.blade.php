@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')


<div class="inner-page">
    <div class="container">

        <div class="order-done text-center">
            <img class="img-fluid" src="/dukaan/images/icons/order-done.png" alt=""/>
            <h1>@lang('front.Your order') <a href='index.php?page=order-details'>#{{$order->id}}</a> @lang('front.completed')!</h1>
            <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Continue Shopping')</a>
        </div>
    </div>
</div>
@endsection

@section('vuejs')
    <script>

        function VueData(data){

            data.checkout_steps = getCheckoutsteps(true,true,true,true,false,false,false,true);
            return data;
        }

        function VueMethods(methods){

            return methods;
        }
    </script>

@endsection

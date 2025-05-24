@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class='row'>
            <div class="col-md-3">
                <x-dukaan-user-menu/>
            </div>
            <div class="col-md-9">

                <div class="products-container" v-if="this.favourateProducts.length">
                    <div class="row">
                        @foreach ($favourites as $product)
                            <div class="col-md-3 col-6" 
                            v-if="favourateProducts.filter(favProduct => favProduct == parseInt('{{$product->id}}')).length"
                            >
                                <x-dukaan-product-item :product="$product"/>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="container" v-else>
                    <div class="order-done emptycart text-center">
                        <img class="img-fluid" src="/dukaan/images/icons/empty-wishlist-2.png" alt=""/>
                        <h1>@lang('front.Your Wishlist is currently empty')!</h1>
                        <p>
                            @lang('front.Before proceed to checkout you must add some products to your shopping cart You will find a lot of interesting products on our "Shop" page').
                        </p>
                        <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Start Shopping')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
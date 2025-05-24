@extends('apps::dukaan.components.layouts.main')
@section( 'title',$package->title)
@section( 'content')
    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="sp-wrap">
                        <a href="{{$package->getFirstMediaUrl('images')}}"><img src="{{$package->getFirstMediaUrl('images')}}" class="img-responsive" alt="img"></a>
                        {{--                        <a v-for="photo in product.gallary" :key="'photo_gallary_' + photo.id" :href="photo.url" ><img :src="photo.url" class="img-responsive" alt="img"></a>--}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content">
                        <div class="pro-head d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="pro-name">{{$package->title}}</h1>
                                <div class="price">
                                    <span>{{$package->price}}</span> {{CURRENCY}}
                                    <del class="discount"> <span class="allPrice">{{$package->products()->sum('price')}}</span> {{CURRENCY}}</del>
                                </div>
                            </div>
                        </div>

                        <div class='block' style="margin-top: 30px">
                            @foreach($package->products as $singleProduct)
                                <div class="form-group" style="margin: 20px 0">
                                    <img  src="{{$singleProduct->getFirstMediaUrl('images')}}" style="width: 100px;height: 100px;display: inline-block" class="img-responsive pull-left" alt="img">
                                    <div class="form-check-label check-note pull-left" style="display: inline-block;padding: 20px 10px" for="flexCheckDefault80">
                                        {{$singleProduct->title}}
                                        <br> {{__('package::dashboard.offers.form.prices.qty')}}:  1x
                                        <br> {{__('package::dashboard.offers.form.prices.price')}}: {{$singleProduct->price}} {{CURRENCY}}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            @endforeach
                        </div>

                        @if((setting('other.selling_on_site') ?? 1) == 1)
                            <div class='block'>
                                <div>
                                    <button id="#package-btn-{{$package->id}}" class="btn addto-cart theme-btn"
                                            :disabled="packageBtns && packageBtns.filter(package => package.id == {{$package->id}} && package.showLoader).length"
                                            @click="addItemToCart('{{ route('frontend.shopping-cart.create-or-update', [$package->id]) }}','{{$package->id}}','package')" style="display: block;width: 100%">

                                        <span v-if="packageBtns && packageBtns.filter(package => package.id == {{$package->id}} && package.showLoader).length">
                                            <x-dukaan-btn-loader/>
                                        </span>
                                        <span v-else class="btn-text">{{ __('catalog::frontend.products.add_to_cart') }}</span>

                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dukaan-add-cart-modal/>
@endsection

@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')
<div class="inner-page" v-if="!cartIsImpty()">
    <div class="container">
        <div class="row cart-page">

            <div class="col-md-8">
                <div class="cart-list">
                    <div class="cart-item" v-for="item in cartItems()" :key="'shopping_cart_items_' + item.id">
                        <div class="d-flex align-items-center cart-item-content">
                            <div class="img-block">
                                <img :src="item.attributes.product.photo" :alt="item.name">
                            </div>
                            <div style="padding: 10px">
                                <h3 style="margin-bottom: 0">
                                    <a :href="item.attributes.product.show_route">
                                        @{{item.name}} <br>
                                        @{{item.attributes.secondary_title}}
                                    </a>
                                </h3>
                                <ul class="options" v-if="item.attributes.product_type != 'offer'">
                                    <li><span>{{ __('apps::frontend.master.qty') }}:</span> @{{item.qty}}X</li>
                                </ul>
                                <span class="pro-price">  @{{item.price}}</span>
                            </div>
                        </div>
                        <div class="quantity text-center">
                            <div class="row" v-if="item.qty_loader "><x-dukaan-sppiner-loader/> </div>

                            <div v-else v-if="item.attributes.product_type == 'product'" class="buttons-added d-flex align-items-center justify-content-between">

                                <button class="sign" @click="updateQty('decrement',item.attributes.product.id,item.qty)"><i class="fa fa-minus"></i></button>
                                <div class="qty-text">
                                    <input type="text" :value="item.qty" title="{{ __('apps::frontend.master.qty') }}" class="input-text qty text" size="1" disabled>
                                </div>
                                <button class="sign" @click="updateQty('increment',item.attributes.product.id,item.qty)"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="cart-options d-flex">
                            <button class="delete-item btn" v-if="!item.attributes.product.cart_loader" @click="removeItemFromCart(item.attributes.product.id,item.attributes.product_type)">
                                <i class="ti-trash"></i>
                            </button>
                            <div v-else class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="order-summery">
                    <h2>@lang('front.cart summary')</h2>
                    <div class="cart-summery-content">
                        <ul class="order-total">
                            {{-- <li>
                                <h5>SubTotal</h5>
                                <span>KD240</span>
                            </li>
                            <li>
                                <h5>Delivery Free</h5>
                                <span>KD10</span>
                            </li> --}}
                            <li class="total-amount">
                                <h5>{{ __('catalog::frontend.cart.subtotal') }}</h5>
                                <span>@{{cartSubTotal()}} </span>
                            </li>
                        </ul>
                        <a class="btn theme-btn d-block mt-20"  href="{{ route('frontend.checkout.index') }}">
                            {{ __('catalog::frontend.cart.checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="inner-page" v-else>
    <div class="container">
        <div class="order-done emptycart text-center">
            <img class="img-fluid" src="/dukaan/images/icons/empty-bag.png" alt=""/>
            <h1>@lang('front.Your Cart Is Currently Empty')!</h1>
            <p>
                @lang('front.We have thousands of items available across our wide range of sellers Start ordering today')!
            </p>
            <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Start Shopping')</a>
        </div>
    </div>
</div>

@endsection

@section('vuejs')
<script>
    function VueData(data){
        data.checkout_steps = getCheckoutsteps(true);
        return data;
    }

    function VueMethods(methods){

        methods.updateQty = function(type, productId, qty){

            let action = '';
            let productType = '';
            let item = this.findItemById(productId);
            var qty = item.qty;
            qty = type == 'decrement' ? qty - 1 : qty + 1;

            if(qty >= 1){

                item.qty_loader = true;
                if(item.attributes.product_type == 'product'){

                    action = "{{route('frontend.shopping-cart.create-or-update',['slug'])}}";
                    action = action.replace('slug', item.attributes.product.slug);
                    productType = 'product';
                }else{

                    action = "{{route('frontend.shopping-cart.create-or-update', ['slug','id'])}}";
                    action = action.replace('slug', item.attributes.product.parent_slug);
                    action = action.replace('id', item.attributes.product.id);
                    productType = 'variation';
                }

                axios.post(action,{

                    qty: qty,
                    request_type: 'cart',
                    product_type: productType,

                }).then(response => {

                    let newProduct = response.data.data.items.find(newItem => newItem.attributes.product.id == productId);
                    this.cart.cart_subTotal = response.data.data.cart_subTotal;
                    this.cart.cart_total = response.data.data.cart_total;
                    item.qty = newProduct.qty;
                    item.qty_loader = false;
                }).catch(function (error) {
                    // handle error
                    alertMessage('error',error.response.data.errors);
                    item.qty_loader = false;
                });
            }
        }

        return methods;
    }
</script>
@stop

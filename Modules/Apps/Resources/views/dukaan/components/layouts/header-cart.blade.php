
@if((setting('other.selling_on_site') ?? 1) == 1)
    <button class="cart-btn header-btn" :disabled="cartIsImpty()">
        <span class="shopping-desc">
            <span class="shopping-price" v-if="!cartIsImpty()"><i class="counter">@{{cartCount()}}</i> <span class="btn-text2">@{{cartSubTotal()}}  </span></span>
            <img class="img-fluid icon-img" src="/dukaan/images/icons/shopping-cart.png" alt="" />
        </span>
        <span class="btn-text">{{ __('catalog::frontend.cart.shipping_cart') }}</span>
    </button>


    @if (!in_array(request()->route()->getName(),['frontend.shopping-cart.index', 'frontend.checkout.complete']))
        <div class="user-side-modal side-modal" v-show="!cartIsImpty()">
            <div class="side-modal-head">
                <button class="btn close-modal"><i class="ti-close"></i> {{ __('apps::frontend.master.close') }}</button>
                <h3 class="side-modal-title">{{ __('catalog::frontend.cart.shipping_cart') }}</h3>
            </div>
            <div class="side-modal-content d-flex">
                <div class="cart-list">

                    <div class="cart-item" v-for="item in cartItems()" :key="'header_cart_items_' + item.id">
                        <div class="d-flex">
                            <div class="img-block">
                                <img :src="item.attributes.product.photo" :alt="item.name">
                            </div>
                            <div style="padding: 10px">
                                <h3 style="margin-bottom: 0;"><a :href="item.attributes.product.show_route">@{{item.name}}</a></h3>
                                <ul class="options">
                                    <li><span>{{ __('apps::frontend.master.qty') }}:</span>@{{item.qty}}X</li>
                                </ul>
                                <span class="pro-price">  @{{item.price}}</span>
                            </div>
                        </div>
                        <div class="cart-options">
                            <button class="delete-item btn" v-if="!item.attributes.product.cart_loader" @click="removeItemFromCart(item.attributes.product.id,item.attributes.product_type)">
                                <i class="ti-trash"></i>
                            </button>
                            <div v-else class="spinner-grow spinner-grow-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="side-modal-footer">
                    <ul class="order-total">
                        <li class="total-amount">
                            <h5>{{ __('catalog::frontend.cart.subtotal') }}</h5>
                            <span>@{{cartSubTotal()}} </span>
                        </li>
                    </ul>
                    <div class="side-modal-btns">
                        <a class="btn theme-btn" href="{{ route('frontend.checkout.index') }}">
                            {{ __('catalog::frontend.cart.checkout') }}
                        </a>
                        <a class="btn theme-btn-sec" href="{{ route('frontend.shopping-cart.index') }}">{{ __('catalog::frontend.cart.cart_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!in_array(request()->route()->getName(),['frontend.shopping-cart.index']))
        <!------------------ Sticky Cart ------------------->
        <div class="sticky-cart" v-if="!cartIsImpty()">
            <div class="container">
                <div class="content d-flex justify-content-between align-items-center">
                    <div class="left">
                        <h6>{{ __('apps::frontend.master.you_have') }} <span>@{{cartCount()}}</span> {{ __('apps::frontend.master.items') }}</h6>
                    </div>
                    <div class="middle">
                        <h5><span>@{{cartCount()}}</span> {{ __('catalog::frontend.cart.subtotal') }}: @{{cartSubTotal()}}  </h5>
                    </div>
                    <a class="btn theme-btn" href="{{ route('frontend.shopping-cart.index') }}">{{ __('catalog::frontend.cart.cart_details') }}</a>
                </div>
            </div>
        </div>
    @endif
@endif

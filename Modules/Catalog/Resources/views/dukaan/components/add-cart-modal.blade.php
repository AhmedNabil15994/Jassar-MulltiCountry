
@if((setting('other.selling_on_site') ?? 1) == 1)
    <div class="modal fade addcart-modal" id="addcart_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>

                <div class="addcart-header text-center">
                    <img class="img-fluid"  src="/dukaan/images/icons/bag-2.svg"/>
                    <h5>@lang('catalog::frontend.cart.add_successfully')</h5>
                </div>
                <div class="product-style2 d-flex" v-if="succesfullyAddedItem">
                    <div class="img-block">
                        <img class="img-fluid" :src="succesfullyAddedItem.attributes.product.photo" :alt="succesfullyAddedItem.name"/>
                    </div>
                    <div>
                        <a class="pro-name" :href="succesfullyAddedItem.attributes.product.show_route">@{{succesfullyAddedItem.name}}</a>
                        <ul class="options">
                            <li><span>{{ __('apps::frontend.master.price') }}:</span>  @{{succesfullyAddedItem.price}} </li>
                            <li><span>{{ __('apps::frontend.master.qty') }}:</span> @{{succesfullyAddedItem.qty}}</li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex addcart-footer">
                    <a class="btn theme-btn-sec" @click="closeModal('addcart_modal')">@lang('apps::frontend.master.continue_shopping')</a>
                    <a class="btn theme-btn" href="{{ route('frontend.shopping-cart.index') }}">{{ __('catalog::frontend.cart.cart_details') }}</a>
                </div>
            </div>
        </div>
    </div>

{{--    <div class="modal fade addpro-modal" id="addpro_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content" style="padding: 20px">--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>--}}

{{--                <div class="addcart-header text-center">--}}
{{--                    <img class="img-fluid"  src="/dukaan/images/icons/bag-2.svg"/>--}}
{{--                    <h5>@lang('catalog::frontend.cart.offer_products')</h5>--}}
{{--                </div>--}}
{{--                <div class="product-style2 offer_product d-flex" @click="setSelectedProducts(succesfullyAddedItem.attributes.product.id,item.id, item.base_price , succesfullyAddedItem.attributes.product.qty )" style="margin-bottom: 10px" v-for="item in succesfullyAddedItem.attributes.product.products" v-if="succesfullyAddedItem">--}}
{{--                    <div class="img-block">--}}
{{--                        <img class="img-fluid" :src="item.photo" :alt="item.title"/>--}}
{{--                    </div>--}}
{{--                    <div style="padding: 0 10px">--}}
{{--                        <a class="pro-name"  >@{{item.title}}</a>--}}
{{--                        <ul class="options">--}}
{{--                            <li><span>{{ __('apps::frontend.master.price') }}:</span>  <span v-html="item.price"></span> </li>--}}
{{--                            <li><span>{{ __('apps::frontend.master.qty') }}:</span> @{{succesfullyAddedItem.attributes.product.total_qty}}</li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="d-flex addcart-footer">--}}
{{--                    <a class="btn theme-btn-sec" @click="closeModal('addcart_modal')">{{ __('catalog::frontend.cart.addproducts') }}</a>--}}
{{--                    <a class="btn theme-btn" href="{{ route('frontend.shopping-cart.index') }}">{{ __('catalog::frontend.cart.cart_details') }}</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="cart-item row" v-for="item in succesfullyAddedItem.attributes.product.products" >--}}
{{--        <div class="d-flex">--}}
{{--            <div class="img-block">--}}
{{--                <img :src="item.photo" :alt="item.title">--}}
{{--            </div>--}}
{{--            <div style="padding: 10px">--}}
{{--                <h3 style="margin-bottom: 0"><a href="#">@{{item.title}}</a></h3>--}}
{{--                <ul class="options">--}}
{{--                    <li><span>{{ __('apps::frontend.master.qty') }}: </span> @{{succesfullyAddedItem.attributes.product.total_qty}}X</li>--}}
{{--                </ul>--}}
{{--                <span class="pro-price">@{{item.total_price}}</span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endif

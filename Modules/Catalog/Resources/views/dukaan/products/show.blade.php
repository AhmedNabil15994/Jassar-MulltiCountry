@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="sp-wrap">
                        <a :href="product_photo"><img :src="product_photo" class="img-responsive" alt="img"></a>

                        <a v-for="photo in product.gallary" :key="'photo_gallary_' + photo.id" :href="photo.url" ><img :src="photo.url" class="img-responsive" alt="img"></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content">
                    <div class="pro-head d-flex justify-content-between align-items-center">
                        <div>
                            <a class="pro-cat">@{{product.category}}</a>
                            <h1 class="pro-name">@{{product_title}}</h1>
                            <div v-html="product_price"></div>
                        </div>
                        @auth
                        <button @click="addToFavourites(product.id)"
                            :class="favourateProducts.filter(favProduct => favProduct == product.id).length ? `btn addtowishlist active` : `btn addtowishlist` ">
                            <i class="fas fa-heart"></i>
                        </button>
                        @endauth
                    </div>
                    <p class="pro-desc" v-html="product.description"></p>

                    <div class='block'>
                        <h4 class='inner-title'>{{ __('catalog::frontend.products.sku') }}: #@{{product_sku}}</h4>
                    </div>
                    <div v-if="product.product_flag == 'variant'">
                        <div class='block' v-for="product_option in product.options" :key="'product_option_' + product_option.id">
                            <h4 class='inner-title'>@{{product_option.option.title}}</h4>
                            <div :class="product_option.option.value_type == 'color' ? 'pro-color d-flex' : 'pro-size d-flex align-items-center'">
                                <button class='sizebtn' v-for="option_value in product_option.option_values" :key="'option_value_' + option_value.id"
                                @if((setting('other.selling_on_site') ?? 1) == 1) @click="selectOption(product_option.option.id,option_value.id)" @endif>
                                    <span class="color-block" v-if="product_option.option.value_type == 'color'" :style="'background:' + option_value.color"></span>
                                    @{{option_value.title}}
                                </button>
                                {{-- <button class='btn-guide' data-bs-toggle="modal" data-bs-target="#exampleModalLong">@lang('front.Size Guide')</button> --}}
                            </div>
                        </div>
                    </div>
                    @if((setting('other.selling_on_site') ?? 1) == 1)
                        <div class='block pro-qty-desk' v-if="activeAddToCart">
                            <div class="d-flex align-items-center justify-content-between qty-ops">
                                <div class="quantity text-center">
                                    <div class="buttons-added d-flex align-items-center justify-content-between">
                                        <button class="sign" @click="decrementQty()"><i class="fa fa-minus"></i></button>

                                        <div class="qty-text">
                                            <input type="text" v-model="qty" title="Qty" class="input-text qty text" size="1" disabled>
                                        </div>
                                        <button class="sign" @click="incrementQty()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <button class="btn theme-btn" @click="addToCart()">@lang('front.Add to cart')</button>
                            </div>
                        </div>
                        <div class='row' style="padding: 0px 47%;"  v-if="cartLoader">
                            <x-dukaan-sppiner-loader/>
                        </div>
                    @endif
                    <div class="share-pro d-flex align-items-center">
                        <h4 class='mb-0'>@lang('front.Share'): </h4>
                        <div class="share-btns">
                          <a href="https://www.facebook.com/sharer/sharer.php?u={{ Illuminate\Support\Facades\URL::current() }}"
                            target="_blank"><i class="fab fa-facebook-f"></i>
                          </a>
                          <a href="http://twitter.com/share?text={{ $product['title'] }}&url={{ Illuminate\Support\Facades\URL::current() }}"
                            target="_blank"><i class="fab fa-twitter"></i></a>
                          <a href="http://pinterest.com/pin/create/button/?url={{ Illuminate\Support\Facades\URL::current() }}"
                            target="_blank">
                            <i class="fab fa-pinterest-p"></i>
                          </a>
                          <a href="https://wa.me?text={{ Illuminate\Support\Facades\URL::current() }}"
                            target="_blank">
                            <i class="fab fa-whatsapp"></i>
                          </a>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>


    @if (count($related_products))
        <div class="section-block mt-20">
            <div class="container">
                <div class="section-title d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ __('catalog::frontend.products.related_products') }}</h2>
                    <a class="seemore-link" href="{{ route('frontend.categories.products',['slug'=> $category[0]->slug]) }}"><span>@lang('front.See more')</span></a>
                </div>
                <div class="home-products owl-carousel">

                    @foreach ($related_products as $prod)
                        <div class="item">
                            <x-dukaan-product-item :product="$prod"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<x-dukaan-add-cart-modal/>

@endsection


@section('vuejs')
<script>
    let product = @json($product);
    let variantPrd = @json($variantPrd);
    let selectedOptions = @json($selectedOptions);
    let selectedOptionsValue = @json($selectedOptionsValue);

    function VueData(data){

        data.product_title = product.title;
        data.product_photo = product.photo;
        data.product_sku = product.sku;
        data.product_price = product.price;
        data.max_qty = 100;
        data.product = product;
        data.qty = 1;
        data.activeAddToCart = product.product_flag == 'variant' ? false : true;
        data.cartLoader = false;
        data.variantPrd = variantPrd;
        data.selectedOptions = selectedOptions;
        data.selectedOptionsValue = selectedOptionsValue;

        return data;
    }

    @if((setting('other.selling_on_site') ?? 1) == 1)
        function VueMethods(methods){

            methods.selectOption = function(optionId,valueId){

                this.selectedOptions[optionId] = valueId;

                if(Object.keys(this.selectedOptions).length == this.product.options.length)
                {
                    this.cartLoader = true;
                    this.activeAddToCart = false;

                    axios.get("{{ route('frontend.get_prd_variation_info') }}",{
                        params: {
                            selectedOptions: this.selectedOptions,
                            product_id:product.id,
                        }
                    }).then(response => {

                        this.variantPrd = response.data.data.variantProduct;
                        this.product_title = this.variantPrd.title;
                        this.product_sku = this.variantPrd.sku;
                        this.product_photo = this.variantPrd.photo;
                        this.product_price = this.variantPrd.price;
                        this.max_qty = this.variantPrd.qty;
                        this.activeAddToCart = true;
                        this.cartLoader = false;
                    }).catch(function (error) {
                        // handle error
                        this.cartLoader = false;
                        this.activeAddToCart = false;
                        alertMessage('error',error.response.data.errors);
                    });
                }
            }

            methods.addToCart = function(){

                let action = '';
                let productType = '';

                if(this.variantPrd){

                    action = "{{ route('frontend.shopping-cart.create-or-update', ['slug','variant_id']) }}";
                    action = action.replace('variant_id', this.variantPrd.id);
                    action = action.replace('variant_id', this.variantPrd.id);
                    productType = 'variation';
                }else{

                    action = "{{ route('frontend.shopping-cart.create-or-update', ['slug']) }}";
                    productType = 'product';
                }

                action = action.replace('slug', this.product.slug);

                this.activeAddToCart = false;
                this.cartLoader = true;

                axios.post(action,{

                    selectedOptions: this.selectedOptions,
                    qty: this.qty,
                    request_type: 'product',
                    product_type: productType,

                }).then(response => {

                    this.cart = response.data.data;
                    this.succesfullyAddedItem = this.findItemById(this.cart.new_item_id);
                    this.activeAddToCart = true;
                    this.cartLoader = false;
                    $('#addcart_modal').modal('show');

                }).catch(function (error) {
                    // handle error
                    alertMessage('error',error.response.data.errors);
                    this.activeAddToCart = true;
                    this.cartLoader = false;
                });
            }

            methods.decrementQty = function(){
                if(this.qty > 1)
                    this.qty --;
            }
            methods.incrementQty = function(){
                if(this.max_qty > this.qty)
                    this.qty ++;
            }
            return methods;
        }
    @endif
</script>
@stop

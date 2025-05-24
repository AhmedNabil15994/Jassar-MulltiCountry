@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')

<div class="inner-page">
    <div class="container">
        <div class="row cart-page">
            <div class="col-md-6">
                <div class="order-summery">
                    <h2>@lang('front.Your Order Summery')</h2>
                    <div class="cart-summery-content">
                        <div class="cartsummery-items">
                            <h4>(@{{cart.items.length}}) @lang('front.items in your cart')</h4>
                        </div>
                        <div class="cart-list">
                            <div class="cart-item" v-for="item in cart.items" :key="'checkout_cart_items_' + item.id">
                                <div class="d-flex">
                                    <div class="img-block">
                                        <img :src="item.attributes.product.photo" :alt="item.name">
                                    </div>
                                    <div style="padding: 10px">
                                        <h3 style="margin-bottom: 0"><a :href="item.attributes.product.show_route">@{{item.name}}</a></h3>
                                        <ul class="options">
                                            <li><span>{{ __('apps::frontend.master.qty') }}: </span> @{{item.qty}}X</li>
                                        </ul>
                                        <span class="pro-price">@{{item.price}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="order-total">
                            <li>
                                <h5>{{ __('catalog::frontend.cart.subtotal') }}</h5>
                                <span>@{{cartSubTotal()}} </span>
                            </li>
                            <li>
                                <h5>@lang('front.Delivery Free')</h5>
                                <span>@{{delivery_price.totalDeliveryPrice}}</span>
                            </li>
                            <li class="total-amount">
                                <h5>@lang('front.Total amount')</h5>
                                <span>@{{cartTotal()}} </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="checkout-block">
                    <div class="head">
                        <h3>@lang('front.Payment Method')</h3>
                    </div>
                    <div class="payment-method">
                        <div class="form-check" v-for="method in payment_methods">
                            <input class="form-check-input" type="radio" :value="method.key" :id="'payment_method' + method.key" v-model="selected_payment_method">
                            <label class="form-check-label" :for="'payment_method' + method.key">
                                <img class="" v-if="method.logo" :src="method.logo" :alt="method.title">
                                @{{method.title}}
                            </label>
                        </div>
                    </div>
                </div>

                <x-dukaan-checkout-coupon-form/>

                <div class="side-modal-btns">
                    <button @click="createOrder()" class="btn theme-btn d-block" href="index.php?page=order-done" :disabled="create_order_loader">
                        <span v-if="create_order_loader"><x-dukaan-btn-loader/></span>
                        <span v-else class="btn-text">@lang('front.Place Order')</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('vuejs')
    @include('user::dukaan.profile.addresses.scripts')
    <script>

        function VueData(data){
            let minForDelivery = "{{$deliveryCharge ? $deliveryCharge->min_order_for_free_delivery : 0 }}";
            let deliveryPrice = "{{$deliveryCharge ? $deliveryCharge->delivery : 0 }}";
            data.checkout_steps = getCheckoutsteps(true,true,true,true,false,true);
            data.payment_methods = @json($paymentMethods);
            data.delivery_type = 'direct';
            data.selected_payment_method = null;
            data.delivery_price = {
                original_price:0,
                price:0,
                delivery_time:'',
                totalDeliveryPrice: parseFloat(data.cart.cart_total) > parseFloat(minForDelivery) ? 0 : parseFloat(deliveryPrice) + ' ' + "{{CURRENCY}}",
                total: data.cart.cart_total,
            };

            data.next_loader = false;
            data.create_order_loader = false;
            data.auth_user = '{{auth()->check()? "true" : "false"}}' == 'true' ? true : false;

            //coupon code
            data.add_coupon_loader = false;
            data.remove_coupon_loader = false;
            data.coupon_code = null;
            data.coupon_error = null;

            return addressData(data);
        }


        function VueMethods(methods){

            methods.checkCoupon = function(){

                this.coupon_error = null;

                if(this.coupon_code){

                    this.add_coupon_loader = true;
                    axios.post("{{ route('frontend.check_coupon') }}",{code: this.coupon_code}).then(response => {
                        this.cart = response.data.data;
                        this.add_coupon_loader = false;
                        this.coupon_code = null;
                    }).catch(error => {

                        this.add_coupon_loader = false;
                        this.coupon_error = error.response.data.message;
                    });
                }else{
                    this.coupon_error = '{{__('enter discount number')}}';
                }
            }

            methods.removeCoupon = function(){

                this.remove_coupon_loader = true;
                axios.post("{{ route('frontend.coupon.remove') }}").then(response => {
                    this.cart = response.data.data;
                    this.remove_coupon_loader = false;
                }).catch(error => {

                    this.remove_coupon_loader = false;
                    alertMessage('error', error.response.data.message);
                });
            }


            methods.couponIsImpty = function(){
                return this.cart.conditions.coupon ? false : true;
            }

            methods.createOrder = function(){

                this.create_order_loader = true;
                let data = this.getOrderData();

                axios.post("{{ route('frontend.orders.create_order') }}",data).then(response => {

                    redirect(response.data);
                    alertMessage('success', "{{__('order::frontend.orders.index.alerts.order_success')}}");
                }).catch(error => {
                    this.create_order_loader = false;
                    $.each(error.response.data.errors,function (index,item){
                        for (let i = 0; i < item.length; i++) {
                            alertMessage('error', item[i]);
                        }
                    });
                    // alertMessage('error', error.response.data.message);
                });
            }

            methods.getOrderData = function(){

                let data = {};

                if(this.auth_user){
                    data.address_type = 'selected_address';
                    data.selected_address_id = "{{session()->get('address_id')}}";
                    data.city_name = this.modal_address.city_name;
                }else{
                    data = @json(\Modules\User\Entities\Address::find(session()->get('address_id')));
                    data.address_type = 'known_address';
                    data.city_name = $( "#state_id option:selected" ).text();
                }

                data.payment = this.selected_payment_method;
                data.shipping = {
                    type: this.delivery_type
                };

                return data;
            }

            return addressMethods(methods);
        }
    </script>

@endsection

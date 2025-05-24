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
                <div class="cart-list orders-list">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link active" 
                                id="pills-home-tab" 
                                data-bs-toggle="pill"
                                data-bs-target="#pills-home" 
                                type="button" 
                                role="tab" 
                                aria-controls="pills-home" 
                                aria-selected="true">
                                @lang('front.Opening Orders')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link" 
                                id="pills-profile-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#pills-profile" 
                                type="button" 
                                role="tab" 
                                aria-controls="pills-profile" 
                                aria-selected="false">
                                @lang('front.Closed Orders')
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="order-item" v-if="getOrders(false).length" v-for="order in getOrders(false)">
                               <x-dukaan-order-card/>
                            </div>

                            <div class="container" v-else>
                                <div class="order-done emptycart text-center">
                                    <img class="img-fluid" src="/dukaan/images/icons/empty-box.png" alt=""/>
                                    <h1>@lang('front.No Orders Found')!</h1>
                                    <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Start Shopping')</a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade"  id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="order-item" v-if="getOrders(true).length" v-for="order in getOrders(true)">
                                <x-dukaan-order-card/>
                             </div>

                            <div class="container" v-else>
                                <div class="order-done emptycart text-center">
                                    <img class="img-fluid" src="/dukaan/images/icons/empty-box.png" alt=""/>
                                    <h1>@lang('front.No Orders Found')!</h1>
                                    <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Start Shopping')</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
       
        </div>
    </div>
</div>

@endsection

@section('vuejs')

<script>

    function VueData(data){
        data.orders = @json($orders);
        return data;
    }

    function VueMethods(methods){
        methods.getOrders = function(is_closed) {
            return this.orders.filter(order => order.order_status.is_closed == is_closed);
        }
        return methods;
    }
</script>

@endsection
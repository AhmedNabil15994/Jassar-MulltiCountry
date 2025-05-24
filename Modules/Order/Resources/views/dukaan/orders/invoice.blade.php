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
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <h3 class='inner-title mb-0'><a href="{{route('frontend.orders.index')}}"><i class='ti-arrow-left'></i></a> @lang('front.Order Details')</h3>
                        <span class="order-status delivered" :style="'background:' + order.order_status.color">@{{order.order_status.title}}</span>
                    </div>
                    <div class='order-details'>
                        <div class='order-details-content'>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="inv-logo"><img class="img-fluid" src="{{setting('logo') ?? '/dukaan/images/logo.png'}}" alt=""/></div>
                                <div class="invoice-head">
                                    <p><b>@lang('Order Date'):</b>  @{{order.created_at}}</p>
                                    <p><b>@lang('Order No.'):</b>  #@{{order.id}}</p>
                                </div>
                            </div>
                            <div class="order-payment d-flex justify-content-between">
                                <div class='payment-block'>
                                    <h4>@lang('Client Info')</h4>
                                    <div class='details'>
                                        <p>
                                            @{{order.user.name}} <br>

                                            @{{order.user.email}} <br>
                                            #@{{order.user.id}}<br>
                                            +@{{order.user.calling_code}}@{{order.user.mobile}}
                                        </p>
                                    </div>
                                </div>
                                <div class='payment-block'>
                                    <div class='details'>
                                        <h4>@lang('front.Payment Method')</h4>
                                        <p>@{{order.transaction == 'cache' ? 'cash' : order.transaction}}</p>
                                    </div>
                                    <div class='details'>
                                        <h4>@lang('front.Shipping Address')</h4>
                                        <p>
                                            @{{order.address.username}} <br>
                                            +@{{order.address.phone_code}}@{{order.address.mobile}} <br>

                                            @{{order.address.city_name}} @{{order.address.country.title}}, @lang('front.Block') @{{order.address.block}} <br>
                                            @lang('front.street') @{{order.address.street}}, building @{{order.address.building}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">@lang('front.Item Name')</th>
                                            <th scope="col">@lang('front.Qty')</th>
                                            <th scope="col">@lang('front.Price')</th>
                                            <th scope="col">@lang('front.Total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in order.products">
                                            <td scope="row">@{{product.title}}</td>
                                            <td>@{{product.qty}}</td>
                                            <td>@{{product.selling_price}}</td>
                                            <td>@{{product.total}}</td>
                                        </tr>
                                        <tr v-for="package in order.packages">
                                            <td scope="row">@{{package.title}}</td>
                                            <td>@{{package.qty ?? 1}}</td>
                                            <td>@{{package.selling_price}}</td>
                                            <td>@{{package.total}}</td>
                                        </tr>
                                        <tr v-for="offer in order.offers">
                                            <td scope="row">@{{offer.title}}</td>
                                            <td>@{{offer.qty ?? 1}}</td>
                                            <td>@{{offer.selling_price}}</td>
                                            <td>@{{offer.total}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice-summery">
                                <ul class="">
                                    <li><span>@lang('front.Sub Total'):</span> <b>@{{order.subtotal}}</b></li>
                                    <li><span>@lang('front.Shipping Fees'):</span>  <b>@{{order.shipping}}</b></li>
                                    <li><span>@lang('front.Discount'):</span>  <b>@{{order.discount}}</b></li>
                                    <li><span>@lang('front.Total'):</span>  <b>@{{order.total}}</b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class='btn theme-btn-sec print'><i class="ti-printer"></i> @lang('front.Print Invoice')</button>
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
        data.order = @json($order);
        return data;
    }

    function VueMethods(methods){

        return methods;
    }
</script>

@endsection

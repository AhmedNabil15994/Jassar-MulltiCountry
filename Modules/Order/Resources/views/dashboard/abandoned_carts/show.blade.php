@extends('apps::dashboard.layouts.app')
@section('title', __('order::dashboard.orders.show.title'))
@section('css')
    <style>
        .btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg {

            padding: 12px 20px 10px;
        }

        .hide_admin_tag {
            display: none;
        }

        .well {
            box-shadow: none;
        }
    </style>

@stop

@section('content')
    <style type="text/css">
        .table>thead>tr>th {
            border-bottom: none !important;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            a[href]:after {
                content: none !important;
            }

            .contentPrint {
                width: 100%;
                /* font-family: tahoma; */
                font-size: 16px;
            }

            .invoice-body td.notbold {
                padding: 2px;
            }

            h2.invoice-title.uppercase {
                margin-top: 0px;
            }

            .invoice-content-2 {
                background-color: #fff;
                padding: 5px 20px;
            }

            .invoice-content-2 .invoice-cust-add,
            .invoice-content-2 .invoice-head {
                margin-bottom: 0px;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

        }
    </style>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('order::dashboard.orders.show.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <div class="col-md-8">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered" style="    border: 1px solid #e7ecf1!important">
                        <div class="portlet-title no-print">
                            <div class="caption font-red-sunglo">
                                <i class="font-red-sunglo fa fa-file-text-o"></i>
                                <span class="caption-subject bold uppercase">
                                    {{ __('order::dashboard.orders.show.invoice_customer') }}
                                </span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row invoice-head contentPrint">
                                <div class="col-md-12 col-xs-12" style="    margin-bottom: 30px;">
                                    <div class="invoice-logo row">

                                        <span class="header">
                                            <h3 class="uppercase">#{{ $order->id }}</h3>
                                        </span>
                                        @if (setting('images.logo'))
                                            <span class="image">
                                                <img src="{{ url(setting('images.logo')) }}" alt="" />
                                            </span>
                                        @endif
                                        <span class="order_Status">
                                            <span
                                                style="background-color: {{ json_decode($order->orderStatus->color_label)->value }};padding: 2px 14px; color: #000000; border-radius: 25px; float: none;">
                                                @php
                                                    $lang = locale();
                                                    $currency = '';
                                                @endphp
                                                {{ $order->orderStatus->title->$lang }}
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                @if ($order->orderAddress != null)
                                    <div class="col-md-6 col-xs-6">
                                        <div class="note well">
                                            @if (!is_null($order->orderAddress->state))
                                                <span class="bold uppercase">
                                                    {{ $order->orderAddress->state->city->title->$lang }}
                                                    /
                                                    {{ $order->orderAddress->state->title->$lang }}
                                                </span>
                                            @endif
                                            <br />

                                            @if ($order->orderAddress->state->country)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.governorate') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->state->country->title->$lang }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->block)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.block') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->block }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->street)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.street') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->street }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->building)
                                                <span
                                                    class="bold">{{ __('order::dashboard.orders.show.address.building') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->building }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->floor)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.floor') }}
                                                    :
                                                </span>
                                                {{ $order->orderAddress->floor }}
                                                <br />
                                            @endif

                                            @if ($order->orderAddress->flat)
                                                <span class="bold">{{ __('order::dashboard.orders.show.address.flat') }}
                                                    : </span>
                                                {{ $order->orderAddress->flat }}
                                                <br />
                                            @endif

                                            <span class="bold">{{ __('order::dashboard.orders.show.address.details') }}
                                                :
                                            </span>
                                            {{ $order->orderAddress->address ?? '---' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-xs-6">
                                    <div class="note well">
                                        <div class="company-address">
                                            <h6 class="uppercase">#{{ $order->id }}</h6>
                                            <h6 class="uppercase">
                                                {{ date('Y-m-d / H:i:s', strtotime($order->created_at)) }}
                                            </h6>
                                            <span class="bold">
                                                {{ __('order::dashboard.orders.show.user.username') }} :
                                            </span>
                                            {{ $order->orderAddress->username ?? '---' }}
                                            <br />
                                            <span class="bold">
                                                {{ __('order::dashboard.orders.show.user.mobile') }} :
                                            </span>
                                            {{ $order->orderAddress ? $order->orderAddress->mobile : (isset($order->unknownOrderAddress) ? $order->unknownOrderAddress->receiver_mobile : '') }}
                                            <br />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 table-responsive">
                                    <br>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="invoice-title uppercase text-left">
                                                    {{ __('order::dashboard.orders.show.items.title') }}
                                                </th>
                                                <th class="invoice-title uppercase text-left">
                                                    {{ __('order::dashboard.orders.show.items.price') }}
                                                </th>
                                                <th class="invoice-title uppercase text-left">
                                                    {{ __('order::dashboard.orders.show.items.qty') }}
                                                </th>
                                                <th class="invoice-title uppercase text-left">
                                                    {{ __('order::dashboard.orders.show.items.total') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $customSubTotal = 0;
                                            @endphp
                                            @foreach ($order->allProducts as $product)
                                                <tr>
                                                    <td class="notbold text-left">

                                                        @if ($product->attributes->product->id)
                                                            @if($product->attributes->product_type == 'package')
                                                                <a href="{{ route('dashboard.packages.edit', $product->attributes->product->id) }}">
                                                                    <img class="product_photo"
                                                                         src="{{ asset($product->attributes->image) }}"
                                                                         width="39px" style="margin: 0px 2px;">
                                                                    <span>
                                                                    {{ $product->attributes->product->title }}
                                                                    @foreach($product->attributes->product->products as $packageProduct)
                                                                            <br><a href="{{ route('dashboard.products.edit', $packageProduct->id) }}" target="_blank">{{$packageProduct->title->$lang . ' ' . $packageProduct->price}}</a>
                                                                    @endforeach
                                                                    @php $currency = $product->attributes->product->country->currency;@endphp
                                                                    </span>
                                                                </a>
                                                            @else
                                                                @php $currency = $product->attributes->product->country->currency;@endphp
                                                            <a href="{{ route('dashboard.products.edit', $product->attributes->product->id) }}">
                                                                <img class="product_photo"
                                                                    src="{{ asset($product->attributes->image) }}"
                                                                    width="39px" style="margin: 0px 2px;">
                                                                <span>
                                                                    @if($product->attributes->product_type == 'offer')
                                                                        {{ $product->attributes->product->title }}

                                                                        @foreach($product->attributes->product->products as $packageProduct)
                                                                            <br><a href="{{ route('dashboard.products.edit', $packageProduct->id) }}" target="_blank">{{$packageProduct->title->$lang . ' ' . $product->attributes->product->qty.'x'}}</a>
                                                                        @endforeach
                                                                        @foreach($product->attributes->product->free_products as $packageProduct)
                                                                            @foreach($product->attributes->product->selectedProducts as $selectedItem)
                                                                                @if($selectedItem->product_id == $packageProduct->id)
                                                                                <br><a href="{{ route('dashboard.products.edit', $packageProduct->id) }}" target="_blank">{{$packageProduct->title->$lang . ' ' . $selectedItem->qty.'x'}}</a>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @else
                                                                        {{ $product->attributes->product->title->$lang }}
                                                                    @endif
                                                                    @php $currency = $product->attributes->product->country->currency;@endphp
                                                                </span>
                                                            </a>
                                                            @endif
                                                        @else
                                                            {{ $product->product_title }}
                                                        @endif

                                                        @if ($product->attributes->notes)
                                                            <h5>
                                                                <b>#
                                                                    {{ __('order::dashboard.orders.show.items.notes') }}</b>
                                                                : {{ $product->notes }}
                                                            </h5>
                                                        @endif

                                                    </td>
                                                    @php
                                                        if($product->attributes->product_type == 'offer'){
                                                            $price = $product->price;
                                                        }else{
                                                            $price = $product->attributes->product->price;
                                                        }
                                                    @endphp

                                                    <td class="text-left notbold">
                                                        {{ number_format($price,3) }}
                                                    </td>
                                                    <td class="text-left notbold"> {{ $product->quantity }}
                                                    </td>
                                                    <td class="text-left notbold">
                                                        @if (!empty($product->add_ons_option_ids))
                                                            {{ (floatval(json_decode($product->add_ons_option_ids)->total_amount) + floatval($product->sale_price)) * intval($product->qty)  }}
                                                        @else
                                                            {{ number_format($product->quantity * $price,3) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <thead>

                                            <tr>
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.subtotal') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold"> {{ number_format((int)$order->subtotal,3) }} </th>
                                            </tr>
                                            <tr
                                                style="{{ is_null($order->orderCoupons) || !empty($order->orderCoupons->products) ? 'border-top: 2px solid #d6dae0;' : '' }}">
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.shipping') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ number_format((int)$order->shipping,3) }}</th>
                                            </tr>
                                            <tr
                                                style="{{ is_null($order->orderCoupons) || !empty($order->orderCoupons->products) ? 'border-top: 2px solid #d6dae0;' : '' }}">
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.off') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ number_format((int)$order->discount,3) }}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-left bold">
                                                    {{ __('order::dashboard.orders.show.order.total') }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-left bold">{{ number_format((int)$order->total,3) }} {{$currency->code}}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="margin: 10px;">
                                            <b>{{ __('order::dashboard.orders.show.notes') }}
                                                : </b>
                                            <span>{{ $order->notes ?? '---' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="margin: 10px;">
                                            <b>
                                                <i class="font-red-sunglo fa fa-file-text-o"></i>
                                                {{ __('order::dashboard.orders.show.admin_note') }}
                                                :

                                                <i class="fa fa-edit show_admin_tag no-print"
                                                    style="color:#32c5d2; cursor: pointer;"
                                                    onclick="toggleAdminTag()"></i>
                                                <span class="hide_admin_tag no-print">
                                                    <i class="fa fa-close" style="color:#fa5661; cursor: pointer;"
                                                        onclick="toggleAdminTag()"></i>
                                                </span>
                                            </b>
                                            <span id="admin_note">{{  $order->admin_note ?? '---' }}</span>
                                            <div style="padding: 20px 74px;" class="hide_admin_tag no-print">

                                                {!! Form::open([
                                                    'url' => route('dashboard.orders.admin.note', $order->id),
                                                    'role' => 'form',
                                                    'page' => 'form',
                                                    'class' => 'form-horizontal form-row-seperated updateForm',
                                                    'method' => 'PUT',
                                                    'files' => true,
                                                ]) !!}

                                                <div class="form-group">
                                                    <textarea class="form-control" name="admin_note" rows="8" cols="6">{{ $order->admin_note ?? '' }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" class="submit btn green btn-lg">
                                                        {{ __('apps::dashboard.general.edit_btn') }}
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if (isset($order->delivery_time['date']) && !empty($order->delivery_time['date']))
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.day') }}
                                                    : </b>
                                                <span>{{ $order->delivery_time['date'] ?? '---' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($order->delivery_time['time_from']) && !empty($order->delivery_time['time_from']))
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.time') }}
                                                    : </b>
                                                <span>From:
                                                    {{ $order->delivery_time['time_from'] ?? '---' }}</span>
                                                <span>To: {{ $order->delivery_time['time_to'] ?? '---' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($order->delivery_time['type']) && $order->delivery_time['type'] == 'direct')
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.type') }}
                                                    : </b>
                                                <span>{{ __('order::dashboard.orders.show.delivery_time.direct') ?? '---' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div style="margin: 10px;">
                                                <b>{{ __('order::dashboard.orders.show.delivery_time.message') }}
                                                    : </b>
                                                <span>{{ $order->delivery_time['message'] ?? '---' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 no-print">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    @permission('show_order_change_status_tab')
                    @endpermission


                    @permission('show_order_change_status_tab')
                    @endpermission
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(".refund_qty").bind('keyup mouseup', function() {
            var newQty = 0;
            $('.refund_qty').each(function() {
                let productId = $(this).attr('data-id');
                let fexedqty = $(this).attr('data-fexedqty');
                let newItemQty = $(this).val();
                let outputQty = parseInt(fexedqty)-parseInt(newItemQty);
                $(`#remaining_qty_${productId}`).text('').html(outputQty);
                $(`#remaining_qty_input_${productId}`).val(outputQty);

                newQty += parseInt($(this).val());
            });

            $('#return_qty').text('').append(newQty);
        });

        function toggleAdminTag() {
            $('.hide_admin_tag').toggle();
            $('.show_admin_tag').toggle();
        }

        function requestUpdating(status, data) {

            if (status == 'success') {

                $('#admin_note').text("").append(data.note);
                toggleAdminTag();
            }
        }
    </script>


@endsection

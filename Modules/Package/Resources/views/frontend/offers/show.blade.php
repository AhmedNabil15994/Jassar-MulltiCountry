@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')
    @php $price=0; @endphp;
    @foreach($offer->products as $prod)
        @php $price+=$prod->price; @endphp;
    @endforeach
    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="sp-wrap">
                        <a href="{{$offer->getFirstMediaUrl('images')}}"><img src="{{$offer->getFirstMediaUrl('images')}}" class="img-responsive" alt="img"></a>
{{--                        <a v-for="photo in product.gallary" :key="'photo_gallary_' + photo.id" :href="photo.url" ><img :src="photo.url" class="img-responsive" alt="img"></a>--}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content">
                        <div class="pro-head d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="pro-name">{{$offer->title}}</h1>
                                <div class="price">
                                    <span>{{count($offer->products) > 1 ? $price : $price * $offer->qty}}</span> {{CURRENCY}}
                                    <del class="discount"> <span class="allPrice">{{count($offer->products) > 1 ? $price : $price * $offer->qty}}</span> {{CURRENCY}}</del>
                                </div>
                            </div>
                        </div>
                        <p class="pro-desc">{{$offer->description}}</p>

                        <div class='block' style="margin-top: 30px">
                            @foreach($offer->products as $singleProduct)
                                <div class="form-group" style="margin: 20px 0">
                                    <img  src="{{$singleProduct->getFirstMediaUrl('images')}}" style="width: 100px;height: 100px;display: inline-block" class="img-responsive pull-left" alt="img">
                                    <div class="form-check-label check-note pull-left" style="display: inline-block;padding-top: 20px" for="flexCheckDefault80">
                                        {{$singleProduct->title}}
                                        @if(count($offer->products) == 1)
                                            <br> {{__('package::dashboard.offers.form.prices.qty')}}:  {{$offer->qty}}x
                                        @endif
                                        <br> {{__('package::dashboard.offers.form.prices.price')}}: {{$singleProduct->price}} {{CURRENCY}}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            @endforeach
                        </div>

                        <div class='block'>
                            <h4 class='inner-title'>{{__('package::dashboard.offers.form.choose_free',['qty'=>$offer->free_qty])}}</h4>
                            @foreach($offer->freeProducts as $oneProduct)
                            <div class="form-group" style="margin-top: 35px">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label check-note" for="flexCheckDefault80" style="width: 40%">
                                    <img src="{{$oneProduct->getFirstMediaUrl('images')}}" class="img-responsive" alt="img">
                                    {{$oneProduct->title}}
                                </label>
                                @if((setting('other.selling_on_site') ?? 1) == 1)
                                <div class="quantity text-center" style="display: inline-block;width: 30%;margin: 0 10px">
                                    <div class="buttons-added d-flex align-items-center justify-content-between">
                                        <button class="sign" onclick="decrementQty(this)"><i class="fa fa-minus"></i></button>
                                        <div class="qty-text">
                                            <input type="text" title="Qty" class="input-text qty text" data-product="{{$oneProduct->id}}" data-price="{{$oneProduct->price}}" value="0" max="{{$offer->free_qty}}" min="0" size="1" disabled>
                                        </div>
                                        <button class="sign" onclick="incrementQty(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @if((setting('other.selling_on_site') ?? 1) == 1)
                            <div class='block'>
                                <div>
                                    <button class="btn theme-btn" onclick="addToCart()" style="width: 100%">@lang('front.Add to cart')</button>
                                </div>
                            </div>
{{--                            <div class='row' style="padding: 0px 47%;"  v-if="cartLoader">--}}
{{--                                <x-dukaan-sppiner-loader/>--}}
{{--                            </div>--}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dukaan-add-cart-modal/>

@endsection
@push('last')
    <script>
        let product = @json($offer);
        let price = 0;
        let totalQtyPrice = 0;
        let totalQty = 0;
        let maxQty = {{$offer->free_qty}};
        @foreach($offer->products as $prod)
            price+=parseFloat("{{$prod->price}}");
            @if(count($offer->products) == 1)
            price = parseFloat("{{$prod->price}}") * "{{$offer->qty}}"
            @endif
        @endforeach



        function incrementQty(elem){
            let maxVal = maxQty;
            let oldVal = $(elem).siblings('.qty-text').children('input[type="text"]').val();
            let checkboxElem = $(elem).parents('.form-group').find('.form-check-input').is(':checked');
            if(maxVal > oldVal && checkboxElem && maxVal > totalQty){
                $(elem).siblings('.qty-text').children('input[type="text"]').val(parseInt(oldVal)+1)
                calcQtys();
            }
        }

        function decrementQty(elem){
            let oldVal = $(elem).siblings('.qty-text').children('input[type="text"]').val();
            let checkboxElem = $(elem).parents('.form-group').find('.form-check-input').is(':checked');
            if(oldVal > 0 && checkboxElem){
                $(elem).siblings('.qty-text').children('input[type="text"]').val(parseInt(oldVal)-1)
                calcQtys();
            }
        }

        function calcQtys(){
            totalQtyPrice = 0;
            totalQty = 0;
            $.each($('input.qty'),function (index,item){
                if($(this).parents('.form-group').find('.form-check-input').is(':checked')){
                    totalQtyPrice = parseFloat(totalQtyPrice) + parseFloat( $(item).val() * $(item).data('price'))
                    totalQty= parseInt(totalQty) + parseInt($(item).val());
                }
            });
            $('.allPrice').html(price + totalQtyPrice)
        }

        function addToCart(){
            let selectedProducts = [];
            if(totalQty == maxQty){
                $.each($('.form-check-input'),function (index,item){
                    let itemQty = $(item).parents('.form-group').find('input.qty').val()
                    let product_id = $(item).parents('.form-group').find('input.qty').data('product')
                    if($(item).is(':checked') && itemQty > 0){
                        selectedProducts.push({
                            'product_id': product_id,
                            'qty': itemQty,
                        });
                    }
                });

                let requestData = {
                    request_type: 'general_cart',
                    product_type: 'offer',
                };

                if(price){
                    requestData.price = price;
                    requestData.selectedProducts = selectedProducts;
                }

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    type: "POST",
                    url: '{{ route('frontend.shopping-cart.create-or-update', [':id']) }}'.replace(':id','{{$offer->id}}'),
                    data: requestData,
                    success:function (data){
                        if(data.data.new_item_id){
                            window.location.href= "{{ route('frontend.shopping-cart.index') }}";
                        }
                    },
                    error:function (data){
                        toastr['error'](data.responseJSON.errors);
                    }
                })
            }else{
                toastr['error']("{{__('catalog::frontend.offers.alerts.totalQtyNotEqualOfferFreeProductsQty',['qty'=>$offer->free_qty])}}");
            }

            // axios.post(url,requestData).then(response => {
            //
            //     this.cart = response.data.data;
            //     this.succesfullyAddedItem = this.findItemById(this.cart.new_item_id,type);
            //
            //     $('#addcart_modal').modal('show');
            //
            // });
        }
    </script>
@endpush


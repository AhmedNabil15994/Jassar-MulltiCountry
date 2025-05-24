@extends('apps::dukaan.components.layouts.main')
@section( 'title',__('offers'))
@push('styles')
  <style>
    .p-50{
      padding:50px;
    }
    .mt-15{
      margin-top: 15px;
    }
    .product-block .img-block{
        height: 180px !important;
    }
  </style>
@endpush
@section( 'content')
<div class="inner-page bg-color-dark-one">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="products-container">
          <div class="row">
            @foreach($offers as $key => $offer)
            <div class="col-md-3 col-6">
                <div class="product-block">
                    <a href="{{route('frontend.offers.show',['slug'=>$offer->title])}}" class="img-block">
                        <img class="img-fluid" src="{{ $offer->image }}" alt="{{ $offer->title }}"/>
                    </a>
                    <div class="content-block">
                        <a class="pro-name" href="{{route('frontend.offers.show',['slug'=>$offer->title])}}">{{ $offer->title }}</a>
                        <p>
                            @foreach($offer->products as $product)
                                <a style="color: inherit" href="{{route('frontend.products.index',['slug'=>$product->slug])}}" target="_blank"> {{$product->title}}</a><br>
                            @endforeach
                        </p>
                    </div>
                    <div class="content-block flex">
                        <a href="{{route('frontend.offers.show',['slug'=>$offer->title])}}"  class="btn theme-btn">
                            <span class="btn-text">{{ __('catalog::frontend.offers.view') }}</span>
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<x-dukaan-add-cart-modal/>

@endsection

@push('scripts')
  <script>
    $(function (){
      $('.price .select2_package').on('change',function (){
        $(this).parents('.price').find('.the_price').empty().html($(this).children('option:selected').data('item'))
      })
    });
  </script>
@endpush

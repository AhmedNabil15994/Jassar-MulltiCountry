@extends('apps::dukaan.components.layouts.main')
@section( 'title',__('packages'))
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
            @foreach($packages as $key => $package)
            <div class="col-md-3 col-6">
                @php $packagePrice = $package->prices()->first(); @endphp
                <div class="product-block">
                    <a href="{{ route('frontend.packages.show',[$package->id])  }}" class="img-block">
                        <img class="img-fluid" src="{{ $package->image }}" alt="{{ $package->title }}"/>
                    </a>
                    <div class="content-block">
                        <a class="pro-name" href="{{ route('frontend.packages.show',[$package->id])  }}">{{ $package->title }}</a>
                        <p>
                            @foreach($package->products as $product)
                                <a style="color: inherit" href="{{route('frontend.products.index',['slug'=>$product->slug])}}" target="_blank"> {{$product->title}}</a><br>
                            @endforeach
                        </p>
                        <div class="price">
                            <div class="the_price mt-15"> {{$package->price}} {{CURRENCY}}</div>
                        </div>
                    </div>
                    <div class="content-block flex">
                        <a href="{{route('frontend.packages.show',[$package->id])}}"  class="btn theme-btn">
                            <span class="btn-text">{{ __('catalog::frontend.packages.view') }}</span>
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

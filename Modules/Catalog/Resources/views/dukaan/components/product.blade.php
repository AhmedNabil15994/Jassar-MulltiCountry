

<div class="product-block">
    @if(isset($product->offer))
    <div class="sale">{{locale() == 'ar' ? 'خصم ' : 'Discount '}} {{(int) $product->offer->percentage}}% </div>
    @endif
    <a href="{{ $product->show_route }}" class="img-block">
        @if($product->has_offer)
            <x-dukaan-product-tag title="Sale {{$product->offer_percentage}}%"/>
        @endif

        @foreach($product->tags as $tag)
            <x-dukaan-product-tag :title="$tag->title" :background="$tag->background" :color="$tag->color"/>
        @endforeach

        <img class="img-fluid" src="{{ $product->photo }}" alt="{{ $product->title }}"/>
    </a>
    <div class="content-block">
        <a class="pro-name" href="{{ $product->show_route }}">{{ $product->title }}</a>
        <div class="price"> {!! $product->price !!}</div>
    </div>
    <div class="content-block flex">
        @auth()
            <button
                @click="addToFavourites('{{ $product->id }}')"
                :class="favourateProducts.find(favProduct => favProduct == parseInt('{{$product->id}}'))
            ? 'heart active' : 'heart' "
                type="button"
            >
                <i class="fas fa-heart"></i>
            </button>
        @endAuth
        @if((setting('other.selling_on_site') ?? 1) == 0 || $product->product_flag != 'single')
            <a class="btn addto-cart theme-btn" href="{{ $product->show_route }}">{{ __('catalog::frontend.products.show_product') }}</a>
        @else
            <button id="#product-btn-{{$product->id}}" class="btn addto-cart theme-btn" :disabled="productBtns && productBtns.filter(product => product.id == {{$product->id}} && product.showLoader).length"
                @click="addItemToCart('{{ route('frontend.shopping-cart.create-or-update', [$product->slug]) }}','{{$product->id}}')">
                <span v-if="productBtns && productBtns.filter(product => product.id == {{$product->id}} && product.showLoader).length"><x-dukaan-btn-loader/></span>
                <span v-else  class="btn-text">{{ __('catalog::frontend.products.add_to_cart') }}</span>

            </button>
        @endif
    </div>
</div>

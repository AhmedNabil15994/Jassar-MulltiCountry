
<button class="search-btn header-btn">
    <img class="img-fluid icon-img" src="/dukaan/images/icons/search.svg" alt=""/>
        <span class="btn-text">@lang('front.Search')</span>
</button>

<div class="search-modal">
    <div class="search-form container">
        <div class="side-modal-head">
            <button class="btn close-modal"><i class="ti-close"></i> @lang('front.Close')</button>
        </div>
            <div class="form-group search-input">
                <input type="text" id="tags" v-model="search_key" @keyup="getProducts()" placeholder="@lang('front.Search for product')"/>
                <button class='btn' type="submit"><i class="ti-search"></i></button>
            </div>
            <div class="eventInsForm">
                <div class="cart-list">

                    <div class="row" v-if="search_loader">
                        <x-dukaan-sppiner-loader/>
                    </div>
                    <div v-if="search_items.length && !search_loader">
                        <div class="cart-item"  v-for="item in search_items" :key="'search_item'+item.id">
                            <div class="d-flex align-items-center">
                                <div class="img-block">
                                    <img :src="item.photo" :alt="item.title">
                                </div>
                                <div>
                                    <h3><a :href="item.show_route">@{{item.title}}</a></h3>
                                    
                                    <span v-html="item.price"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container" v-if="!search_items.length && search_key != '' && search_not_found &&  !search_loader">
                        <div class="order-done emptycart text-center">
                            <img class="img-fluid" src="/dukaan/images/icons/empty-box.png" alt=""/>
                            <h1>@lang('front.No Prodcts Found')!</h1>
                            <a href="{{route('frontend.categories.products')}}" class="btn theme-btn mt-20">@lang('front.Start Shopping')</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
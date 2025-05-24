<div class="previous-address">
    <div class="d-flex justify-content-end mb-20">
        <button class="btn theme-btn" @click="openCreateAddressModal()">@lang('front.New address')</button>
    </div>
    <div v-if="addresses.length" v-for="address in addresses">
        <x-dukaan-address-card/>
    </div>
    <div class="container" v-else>
        <div class="order-done emptycart text-center">
            <img class="img-fluid" src="/dukaan/images/icons/empty-box.png" alt=""/>
            <h1>@lang('front.Addresses is empty')</h1>
        </div>
    </div>
</div>

<x-dukaan-address-modal/>
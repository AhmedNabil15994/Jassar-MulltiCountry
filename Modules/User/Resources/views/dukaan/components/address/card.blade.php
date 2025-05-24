
<div class="d-flex address-block align-items-center">
    <div class="flex-1">
        <p class="d-flex">
            <span class="d-inline-block right-side">@lang('front.Address Name') </span>
            <span class="d-inline-block left-side">  @{{address.username}}</span>
        </p>
        <p class="d-flex">
            <span class="d-inline-block right-side"> @lang('front.Address')</span>
            <span class="d-inline-block left-side">@{{address.address}}</span>
        </p>
        <p class="d-flex">
            <span class="d-inline-block right-side"> @lang('front.Phone no').</span>
            <span class="d-inline-block left-side"> +@{{address.phone_code}}@{{address.mobile}} </span>
        </p>
    </div>
    <div class="justify-content-end address-operations">
        <button class="btn edit-address" @click="openEdit(address.id)"><i class="ti-pencil-alt"></i> @lang('front.Edit')</button>

        <button v-if="!address.delete_loader"  class="btn delete-address" @click="deleteAddress(address.id)"><i class="ti-trash"></i> @lang('front.Delete')</button>
        <div v-else class="spinner-grow spinner-grow-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>

{{--        <button :class="'btn ' + (address.default ? 'theme-btn-sec' : 'mark-default')" @click="makeDefault(address.id)">--}}
{{--            <i class="ti-star" v-if="address.default"></i>--}}
{{--            {{!in_array(request()->route()->getName(),['frontend.checkout.index']) ? __('front.Default') : __('front.Select')}}--}}
{{--            @lang('front.Address')--}}
{{--        </button>--}}
    </div>
</div>

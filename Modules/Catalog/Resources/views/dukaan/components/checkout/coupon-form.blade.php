<div class="checkout-block">
    <div class="head">
        <h3>Discount Codes <i class="ti-tag"></i> </h3>
    </div>
</div>

<form method="post" action="#">
    <div class="row">
{{--        <div class="form-group col-md-12 side-apply-btns">--}}
{{--            <input type="text" class="form-control" name="text" placeholder="Coupon Code">--}}
{{--            <button class="btn theme-btn-sec">Apply</button>--}}
{{--        </div>--}}
        <div class="form-group col-md-12 side-apply-btns" v-if="couponIsImpty()">
                <input type="text" v-model="coupon_code" class="form-control coupon-input"
                       placeholder=" {{__('enter discount number')}}">
                <button class="btn coupon-btn theme-btn-sec"  @click="checkCoupon()"  style="margin:0px 10px;" :disabled="add_coupon_loader">
                    <span v-if="add_coupon_loader"><x-dukaan-btn-loader/></span>
                    <span v-else class="btn-text">{{__('Add')}}</span>
                </button>
            <div class="invalid-feedback invalid-feedback-active" v-if="coupon_error">@{{coupon_error}}</div>
        </div>
        <div v-else>
            <span class="d-inline-block right-side flex-1">
                <div class="alert alert-success" role="alert" style="padding: 5px 5px;">
                    {{__('Your Coupon Discount is:')}} @{{this.cart.conditions.coupon}}
                </div>
            </span>

            <span class="d-inline-block left-side" style=" margin:0px 10px;">
                <span class="coupon_success remove" v-if="!remove_coupon_loader"
                      style="!important;cursor: pointer;" @click="removeCoupon()" title="ازالة الكوبون">
                    <i class="ti-close"></i>
                </span>
                <div v-else class="spinner-grow spinner-grow-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </span>
        </div>

    </div>
</form>

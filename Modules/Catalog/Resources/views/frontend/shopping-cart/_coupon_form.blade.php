<form class="coupon-form" action="{{ url(route('frontend.check_coupon')) }}"
      method="POST" onsubmit="event.preventDefault();">
    @csrf
    <input type="hidden" value="" id="coupon_discount_id" name="coupon_discount_id">
    <input type="hidden" value="" id="coupon_discount_value"
           name="coupon_discount_value">

    <div id="loaderCouponDiv">
        <div class="my-loader"></div>
    </div>

    <div class=" promo-code">
        <h6>{{ __('catalog::frontend.cart.adding_discount_code') }}</h6>
        <div class="d-flex align-items-center">
        <span class="d-inline-block right-side flex-1">
            <input type="text" id="txtCouponCode"
                   placeholder="{{ __('catalog::frontend.cart.enter_discount_number') }}">
        </span>
            <span class="d-inline-block left-side">
            <button class="btn btn-add" type="button" id="btnCheckCoupon">
                {{ __('catalog::frontend.cart.btn.add') }}
            </button>
        </span>
            <span class="d-inline-block left-side remove" title="{{ __('catalog::frontend.cart.btn.remove_coupon') }}">
            <i class="ti-close"></i>
        </span>
        </div>
    </div>

</form>

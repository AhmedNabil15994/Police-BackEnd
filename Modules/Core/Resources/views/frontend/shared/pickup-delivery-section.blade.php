{{--<h2 class="text-center">{{ __('apps::frontend.master.choose_shipping_area') }}</h2>--}}
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="delivery-option text-center">

            <div class="checkboxes radios inline-block">
                <input id="delivery-checkbox" type="radio" name="pickup_delivery_type"
                       onclick="togglePickupDeliveryType('delivery')"
                       value="delivery" {{ is_null(checkPickupDeliveryCookie()) || optional(checkPickupDeliveryCookie())->type == 'delivery' ? 'checked':'' }}>
                <label
                    for="delivery-checkbox">{{ __('apps::frontend.master.enable_delivery') }}</label>
            </div>

            @if(app('defaultRestaurant') && app('defaultRestaurant')->enable_pickup)
                <div class="checkboxes radios inline-block">
                    <input id="pickup-checkbox" type="radio" name="pickup_delivery_type"
                           onclick="togglePickupDeliveryType('pickup')"
                           value="pickup" {{ optional(checkPickupDeliveryCookie())->type == 'pickup' ? 'checked':'' }}>
                    <label
                        for="pickup-checkbox">{{ __('apps::frontend.master.enable_pickup') }}</label>
                </div>
            @endif

        </div>
    </div>
    <div class="col-md-3"></div>
</div>
<br>

<div class="row"
     id="deliveryInfoSection"
     style="{{ optional(checkPickupDeliveryCookie())->type != 'pickup' ? '':'display: none' }}">
    <div
        class="col-md-offset-3 col-md-6 col-xs-12">
        <select id="shippingStatesSelect" class="select-detail searchSelect">
            <option value="">--- {{ __('apps::frontend.master.choose_shipping_area') }} ---</option>
            @foreach ($states as $state)
                <option
                    value="{{ $state->id }}" {{ optional(optional(checkPickupDeliveryCookie())->content)->state_id == $state->id ? 'selected':'' }}>
                    {{ $state->translate(locale())->title }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="row" id="deliveryBranchesSection"
     style="display: {{ optional(checkPickupDeliveryCookie())->type == 'delivery' && count(getBranchesByState(optional(optional(checkPickupDeliveryCookie())->content)->state_id)) > 1 ? '' : 'none' }}; margin-top: 15px;">
    <div
        class="col-md-offset-3 col-md-6 col-xs-12">
        <select id="deliveryBranchesSelect"
                class="select-detail searchSelect">
            <option value="">--- {{ __('apps::frontend.master.choose_shipping_branches') }} ---</option>
            @foreach(getBranchesByState(optional(optional(checkPickupDeliveryCookie())->content)->state_id) as $k => $branch)
                <option value="{{ $branch->id }}"
                        {{ optional(optional(checkPickupDeliveryCookie())->content)->vendor_id == $branch->id ? 'selected' : '' }}
                        data-min_order_id="{{ $branch->deliveryCharge ? $branch->deliveryCharge[0]->min_order_amount : null }}">
                    {{ $branch->translate(locale())->title }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row" id="minOrderAmountSection"
     style="display: {{ optional(checkPickupDeliveryCookie())->type == 'delivery' ? '' : 'none' }}; text-align: center; margin-top: 20px;">
    <div class="col-md-12 col-xs-6">
        <span>{{ __('apps::frontend.master.min_order_amount') }} :</span>
        <b id="minOrderAmountValue">
            {{ $min_order_amount ?? '---' }}
        </b>
    </div>
</div>

<div class="row" id="emptyBranchesSection" style="display: none; text-align: center; margin-top: 20px;">
    <div class="col-md-12 col-xs-6">
        <b>{{ __('apps::frontend.master.shipping_branches_not_available') }}</b>
    </div>
</div>

@if(app('defaultRestaurant') && app('defaultRestaurant')->enable_pickup)
    <div class="row" id="pickupInfoSection"
         style="{{ optional(checkPickupDeliveryCookie())->type == 'pickup' ? '':'display: none' }}">
        <div class="col-md-3"></div>
        <div class="col-md-6 col-xs-12">
            <select id="pickupBranchesSelect"
                    class="select-detail selectBranch searchSelect">
                <option value="">
                    --- {{ __('apps::frontend.master.choose_shipping_branches') }}---
                </option>
                @foreach ($branches as $branch)
                    <option
                        value="{{ $branch->id }}" {{ optional(optional(checkPickupDeliveryCookie())->content)->vendor_id == $branch->id ? 'selected':'' }}>
                        {{ $branch->translate(locale())->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"></div>
    </div>
@endif

<?php

namespace Modules\Catalog\Traits\FrontEnd;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorDeliveryCharge;
use Cart;
use Modules\Vendor\Traits\VendorTrait;

trait CatalogTrait
{
    use ShoppingCartTrait, VendorTrait;

    ### Start - Get products based on selected branch ###
    public function getVendorBasedOnSelectedBranch()
    {
        $vendorId = null;
        if (is_null(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH')))) {
            if (config('setting.other.is_multi_vendors') == 1) {
                // Get Based on first restaurant and first or main branch inside it.
                $vendorQuery = Vendor::branch();
                $check = $vendorQuery->where('is_main_branch', 1)->first();
                $vendorId = is_null($check) ? (Vendor::branch()->first()->id ?? null) : ($vendorQuery->where('is_main_branch', 1)->first()->id ?? null);
            } else {
                // Get Based on default vendor
                $vendorId = $this->getSingleVendor()->id ?? null;
            }
            $shippingBranch = json_encode(['state_id' => null, 'vendor_id' => $vendorId]);
            set_cookie_value(config('core.config.constants.SHIPPING_BRANCH'), $shippingBranch);
        } else {
            // Get Based on selected branch in client browser cookie
            $shippingBranch = json_decode(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH')));
            $vendorId = $shippingBranch->vendor_id;
        }
        return $vendorId;
    }

    ### End - Get products based on selected branch ###

    public function getUserCartToken()
    {
        if (auth()->check())
            $userToken = auth()->user()->id ?? null;
        else {
            if (is_null(get_cookie_value(config('core.config.constants.CART_KEY')))) {
                $userToken = Str::random(30);
                set_cookie_value(config('core.config.constants.CART_KEY'), $userToken);
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY'));
            }
        }
        return $userToken;
    }

    public function saveAndRemoveDeliveryCharge($request, $userToken)
    {
        if (!empty($request->state_id)) {

            $price = VendorDeliveryCharge::where('state_id', $request->state_id)->where('vendor_id', $request->vendor_id)->value('delivery');
            if (!is_null($price) && !is_null($request->vendor_id)) {
                $this->companyDeliveryChargeCondition($request, $price, $userToken);
                return [
                    'deliveryPrice' => $price,
                    'sub_total' => number_format(getCartSubTotal(), 3),
                    'total' => number_format(getCartTotal(), 3),
                ];
            } else {
                if (Cart::session($userToken)->getCondition('company_delivery_fees') != null) {
                    Cart::session($userToken)->removeCartCondition('company_delivery_fees');
                }
                return [
                    'deliveryPrice' => "",
                    'sub_total' => number_format(getCartSubTotal(), 3),
                    'total' => number_format(getCartTotal(), 3),
                ];
            }
        } else {
            Cart::session($userToken)->removeCartCondition('company_delivery_fees');
            // return __('catalog::frontend.checkout.validation.please_choose_state');
        }
        return false;
    }

    public function saveMinOrderAmountCondition($request, $userToken)
    {
        $amount = VendorDeliveryCharge::where('state_id', $request->state_id)->where('vendor_id', $request->vendor_id)->value('min_order_amount');
        Cart::session($userToken)->removeCartCondition('min_order_amount');
        $this->minimumOrderAmountCondition($request, $amount ? number_format($amount, 3) : null, $userToken);
        return $amount;
    }

    public function checkProductAddonsValidation($selectedAddons, $product)
    {
        $userSelections = !empty($selectedAddons) ? array_column($selectedAddons, 'id') : [];
        if ($product->addOns->where('type', 'single')->count() > 0) {
            $productSingleAddons = $product->addOns->where('type', 'single')->pluck('addon_category_id')->toArray();
            $intersectArray = array_values(array_intersect($userSelections, $productSingleAddons));
            if (count($intersectArray) == 0 || (count($intersectArray) > 0 && count($intersectArray) != count($productSingleAddons)))
                return __('cart::api.cart.product.select_single_addons');
            else
                return true;
        }
        return true;
    }

    public function saveContactInfoCookie($data = [])
    {
        set_cookie_value(config('core.config.constants.CONTACT_INFO'), json_encode($data));
    }

    public function checkDeliveryAndMinOrderOnRefresh()
    {
        $companyDeliveryFees = getCartConditionByName(null, 'company_delivery_fees');
        if (!is_null($companyDeliveryFees)) {

            $stateId = $companyDeliveryFees->getAttributes()['state_id'];
            $branchId = $companyDeliveryFees->getAttributes()['branch_id'];

            $query = VendorDeliveryCharge::where('state_id', $stateId)->where('vendor_id', $branchId)->active();
            $delivery = $query->first();

            $deliveryChargeCondition = [];
            $minOrderAmount = null;

            // check if delivery amount is changed.
            if ($delivery && floatval($delivery->delivery) != floatval($companyDeliveryFees->getValue())) {
                $request = new \stdClass;
                $userToken = $this->getUserCartToken();
                $request->state_id = $stateId;
                $request->vendor_id = $branchId;
                // save new delivery charge condition
                $deliveryChargeCondition = $this->saveAndRemoveDeliveryCharge($request, $userToken);
            }

            // check if min order amount is changed.
            if (checkPickupDeliveryCookie()) {
                $minOrderAmount = $query->value('min_order_amount');
                $minOrderAmountCookie = optional(optional(checkPickupDeliveryCookie())->content)->min_order_amount;
                if (floatval($minOrderAmount) != floatval($minOrderAmountCookie)) {
                    // save new min order amount cookie
                    $cookieData = [
                        'type' => checkPickupDeliveryCookie()->type,
                        'content' => [
                            'state_id' => checkPickupDeliveryCookie()->content->state_id,
                            'vendor_id' => checkPickupDeliveryCookie()->content->vendor_id,
                            'min_order_amount' => $minOrderAmount,
                        ]
                    ];
                    set_cookie_value(config('core.config.constants.PICKUP_DELIVERY'), json_encode($cookieData));
                }
            }

            return [
                'new_delivery_cart_condition' => $deliveryChargeCondition,
                'new_min_order_amount' => $minOrderAmount,
            ];
        }
        return [];
    }

    public function checkProductAvailabilityQuery($product, $productData = [])
    {
        $dateNow = Carbon::now();
        $currentTime = $dateNow->format('H:i');
        $currentDayCode = Str::lower($dateNow->format('D'));

        return $product->when(!empty($productData), function ($query) use ($productData) {
            if ($productData['key'] == 'slug') {
                $query->whereTranslation('slug', $productData['value']);
            } else {
                $query->where($productData['key'], $productData['value']);
            }
        })->whereHas('workingTimes', function ($query) use ($currentTime, $currentDayCode) {
            $query->where(function ($query) use ($currentTime, $currentDayCode) {
                $query->where('day_code', $currentDayCode)->where('status', 1)->where('is_full_day', 0);
                $query->whereHas('workingTimeDetails', function ($query) use ($currentTime, $currentDayCode) {
                    $query->where('time_from', '<=', date("H:i:s", strtotime($currentTime)));
                    $query->where('time_to', '>=', date("H:i:s", strtotime($currentTime)));
                });
            });

            $query->orWhere(function ($query) use ($currentDayCode) {
                $query->where('day_code', $currentDayCode)->where('status', 1)->where('is_full_day', 1);
            });
        })->orWhereDoesntHave('workingTimes');
    }

    public function checkRouteLocale($model, $columnValue, $column = 'slug')
    {
        if ($model && $model->translate()->where($column, $columnValue)->first()->locale != locale())
            return false;

        return true;
    }
}

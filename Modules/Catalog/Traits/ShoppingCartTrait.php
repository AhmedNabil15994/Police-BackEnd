<?php

namespace Modules\Catalog\Traits;

use Cart;
use Darryldecode\Cart\CartCollection;
use Darryldecode\Cart\ItemAttributeCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Str;
use Modules\Cart\Entities\DatabaseStorageModel;
use Modules\Catalog\Entities\AddonOption;

trait ShoppingCartTrait
{
    protected $vendorCondition = 'vendor';
    protected $deliveryCondition = 'delivery_fees';
    protected $companyDeliveryCondition = 'company_delivery_fees';
    protected $vendorCommission = 'commission';
    protected $DiscountCoupon = 'coupon_discount';
    protected $minOrderAmountCondition = 'min_order_amount';

    public function addOrUpdateCart($product, $request)
    {
        if (!is_null($product->qty)) {
            $checkQty = $this->checkQty($product);
            if ($checkQty)
                return $checkQty;

            $checkMaxQty = $this->checkMaxQty($product, $request);
            if ($checkMaxQty)
                return $checkMaxQty;
        }

        $vendorStatus = $this->vendorStatus($product);
        if ($vendorStatus)
            return $vendorStatus;

        /*if (!$this->addCartConditions($product))
            return false;*/

        if (!$this->addOrUpdate($product, $request))
            return false;
    }

    // CHECK IF QTY PRODUCT IN DB IS MORE THAN 0
    public function checkQty($product)
    {
        if ($product->qty <= 0)
            return $errors = __('catalog::frontend.products.alerts.product_qty_less_zero');

        return false;
        /*return $errors = new MessageBag([
            'productCart' => __('catalog::frontend.products.alerts.product_qty_less_zero')
        ]);*/
    }

    // CHECK IF USER REQUESTED QTY MORE THAN MAXIMUAME OF PRODUCT QTY
    public function checkMaxQty($product, $request)
    {
        if ($request->qty > $product->qty)
            return $errors = __('catalog::frontend.products.alerts.qty_more_than_max') . $product->qty;

        return false;
    }

    public function vendorExist($product)
    {
        $vendor = Cart::getCondition('vendor');

        if ($vendor) {
            if (Cart::getCondition('vendor')->getType() != $product->vendor->id)
                return $errors = __('catalog::frontend.products.alerts.vendor_not_match');
        }

        return false;
    }

    /*
     * Check if vendor or pharmacy is busy
    */
    public function vendorStatus($product)
    {
        $vendor = $product->product_type == 'variation' ? $product->product->vendor : $product->vendor;
        if ($vendor) {
            ### Check if vendor status is 'opened' OR 'closed'
            if ($vendor->vendor_status_id == 3 || $vendor->vendor_status_id == 4)
                return $errors = __('catalog::frontend.products.alerts.vendor_is_busy');
        }
        return false;
    }

    /*
     * Check if vendor is busy
    */
    public function checkVendorStatus($product)
    {
        ### Check if vendor status is 'opened' OR 'closed'
        if ($product) {
            if ($product->product_type == 'product') {
                if ($product->vendor && ($product->vendor->vendor_status_id == 3 || $product->vendor->vendor_status_id == 4))
                    return __('catalog::frontend.products.alerts.vendor_is_busy');
            } else {
                if ($product->product->vendor && ($product->product->vendor->vendor_status_id == 3 || $product->product->vendor->vendor_status_id == 4))
                    return __('catalog::frontend.products.alerts.vendor_is_busy');
            }
        }

        return false;
    }

    public function productFound($product, $cartProduct)
    {
        if (!$product) {
            if ($cartProduct->attributes->product->product_type == 'product') {
                return $cartProduct->attributes->product->translate(locale())->title . ' - ' .
                    __('catalog::frontend.products.alerts.qty_is_not_active');
            } else {
                return $cartProduct->attributes->product->product->translate(locale())->title . ' - ' .
                    __('catalog::frontend.products.alerts.qty_is_not_active');
            }
        }

        return false;
    }

    public function checkActiveStatus($product, $request)
    {
        if ($product) {

            if ($product->product_type == 'product') {

                if ($product->deleted_at != null || $product->status == 0)
                    return $product->translate(locale())->title . ' - ' .
                        __('catalog::frontend.products.alerts.qty_is_not_active');
            } else {
                if ($product->product->deleted_at != null || $product->product->status == 0 || $product->status == 0)
                    return $product->product->translate(locale())->title . ' - ' .
                        __('catalog::frontend.products.alerts.qty_is_not_active');
            }
        }
        return false;
    }

    public function checkMaxQtyInCheckout($product, $request, $qty)
    {
        if ($product) {

            if ($request->qty > $qty)
                return __('catalog::frontend.products.alerts.qty_more_than_max') . ' ' . $qty . ' - ' .
                    $product->product_type == 'product' ? $product->translate(locale())->title : $product->product->translate(locale())->title;
        }
        return false;
    }

    public function findItemById($id)
    {
        $item = getCartContent()->get($id);
        return $item;
    }

    public function addOrUpdate($product, $request)
    {
        $item = $this->findItemById($product->product_type == 'product' ? $product->id : 'var-' . $product->id);

        if (!is_null($item)) {

            if (!$this->updateCart($product, $request))
                return false;
        } else {

            if (!$this->add($product, $request))
                return false;
        }
    }

    public function add($product, $request)
    {
        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,
            // 'translation' => $product->translations,
            'vendor_id' => $request->vendor_id ?? null,
        ];

        if ($product->product_type == 'variation') {
            $productName = generateVariantProductData($product->product, $product->product->id, json_decode($request->selectedOptionsValue))['name'];
            $attributes['slug'] = Str::slug($productName);
            $attributes['selectedOptions'] = json_decode($request->selectedOptions);
            $attributes['selectedOptionsValue'] = json_decode($request->selectedOptionsValue);
        } else {
            $productName = $product->translate(locale())->title;
            $attributes['slug'] = $product->translate(locale())->slug;
        }

        $addonsOptionsTotal = 0;
        if (isset($request->addonsOptions) && !empty($request->addonsOptions)) {
            $addonsOptionsResult = $this->getAddonsOptionsTotalAmount($request->addonsOptions);
            $addonsOptionsTotal = floatval($addonsOptionsResult['total']);
            $attributes['addonsOptions']['data'] = $request->addonsOptions;
            $attributes['addonsOptions']['total_amount'] = number_format($addonsOptionsTotal, 3);
            $attributes['addonsOptions']['addonsPriceObject'] = $addonsOptionsResult['addonsPriceObject'];
        }

        $cartArr = [
            'id' => $product->product_type == 'product' ? $product->id : 'var-' . $product->id,
            'name' => $productName,
            'quantity' => $request->qty ? $request->qty : +1,
            'attributes' => $attributes,
        ];
        //        $cartArr['price'] = $product->offer ? $product->offer->offer_price : $product->price;
        $cartArr['price'] = $product->offer ? (floatval($product->offer->offer_price) + $addonsOptionsTotal) : (floatval($product->price) + $addonsOptionsTotal);

        if (auth()->check())
            $addToCart = Cart::session(auth()->user()->id)->add($cartArr);
        else {
            if (is_null(get_cookie_value(config('core.config.constants.CART_KEY')))) {
                $cartKey = Str::random(30);
                set_cookie_value(config('core.config.constants.CART_KEY'), $cartKey);
            } else {
                $cartKey = get_cookie_value(config('core.config.constants.CART_KEY'));
            }

            $addToCart = Cart::session($cartKey)->add($cartArr);
        }

        return $addToCart;
    }

    public function updateCart($product, $request)
    {
        if (isset($request->request_type) && $request->request_type == 'product') {

            ### Start Update Cart Attributes ###

            $attributes = [
                'type' => 'simple',
                'image' => $product->image,
                'sku' => $product->sku,
                'old_price' => $product->offer ? $product->price : null,
                'product_type' => $product->product_type,
                'product' => $product,
                'notes' => $request->notes ?? null,
                // 'translation' => $product->translations,
                'vendor_id' => $request->vendor_id ?? null,
            ];

            if ($product->product_type == 'variation') {
                $productName = generateVariantProductData($product->product, $product->product->id, json_decode($request->selectedOptionsValue))['name'];
                $attributes['slug'] = Str::slug($productName);
                $attributes['selectedOptions'] = json_decode($request->selectedOptions);
                $attributes['selectedOptionsValue'] = json_decode($request->selectedOptionsValue);
            } else {
                $productName = $product->translate(locale())->title;
                $attributes['slug'] = $product->translate(locale())->slug;
            }

            $addonsOptionsTotal = 0;
            if (isset($request->addonsOptions) && !empty($request->addonsOptions)) {
                $addonsOptionsResult = $this->getAddonsOptionsTotalAmount($request->addonsOptions);
                $addonsOptionsTotal = floatval($addonsOptionsResult['total']);
                $attributes['addonsOptions']['data'] = $request->addonsOptions;
                $attributes['addonsOptions']['total_amount'] = number_format($addonsOptionsTotal, 3);
                $attributes['addonsOptions']['addonsPriceObject'] = $addonsOptionsResult['addonsPriceObject'];
            }

            ### End Update Cart Attributes ###

            $cartArr = [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->qty ? $request->qty : +1,
                ],
                'attributes' => $attributes,
            ];
            $cartArr['price'] = $product->offer ? (floatval($product->offer->offer_price) + $addonsOptionsTotal) : (floatval($product->price) + $addonsOptionsTotal);
        } else {
            $cartArr = [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->qty ? $request->qty : +1,
                ],
            ];
        }

        if (auth()->check())
            $updateItem = Cart::session(auth()->user()->id)->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);
        else {
            if (is_null(get_cookie_value(config('core.config.constants.CART_KEY')))) {
                $cartKey = Str::random(30);
                set_cookie_value(config('core.config.constants.CART_KEY'), $cartKey);
            } else {
                $cartKey = get_cookie_value(config('core.config.constants.CART_KEY'));
            }
            $updateItem = Cart::session($cartKey)->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);
        }

        if (!$updateItem)
            return false;

        return $updateItem;
    }

    public function addCartConditions($product)
    {
        $orderVendor = new CartCondition([
            'name' => $this->vendorCondition,
            'type' => $product->vendor->id,
            'value' => (string)$product->vendor->order_limit,
            'attributes' => [
                'fixed_delivery' => $product->vendor->fixed_delivery,
            ]
        ]);

        $commissionFromVendor = new CartCondition([
            'name' => $this->vendorCommission,
            'type' => $this->vendorCommission,
            'value' => (string)$product->vendor->commission,
            'attributes' => [
                'commission' => $product->vendor->commission,
                'fixed_commission' => $product->vendor->fixed_commission
            ]
        ]);


        return Cart::condition([$orderVendor, $commissionFromVendor]);
    }

    public function DeliveryChargeCondition($charge, $address)
    {
        $deliveryFees = new CartCondition([
            'name' => $this->deliveryCondition,
            'type' => $this->deliveryCondition,
            'target' => 'total',
            'value' => (string)$charge ? +$charge : +Cart::getCondition('vendor')->getAttributes()['fixed_delivery'],
            'attributes' => [
                'address' => $address
            ]
        ]);

        return Cart::condition([$deliveryFees]);
    }

    public function discountCouponCondition($coupon, $discount_value, $userToken = null)
    {
        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            'value' => (string)$discount_value * -1,
            'attributes' => [
                'coupon' => $coupon
            ]
        ]);

        return Cart::session($userToken)->condition([$coupon_discount]);
    }

    public function saveEmptyDiscountCouponCondition($coupon, $userToken = null)
    {
        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            // 'target' => 'total',
            'value' => (string)0,
            'attributes' => [
                'coupon' => $coupon
            ]
        ]);

        return Cart::session($userToken)->condition([$coupon_discount]);
    }

    public function companyDeliveryChargeCondition($request, $price, $userToken = null)
    {
        $deliveryFees = new CartCondition([
            'name' => $this->companyDeliveryCondition,
            'type' => $this->companyDeliveryCondition,
            'target' => 'total',
            'value' => (string)$price,
            'attributes' => [
                'state_id' => $request->state_id,
                'branch_id' => $request->vendor_id ?? null,
                'address_id' => $request->address_id ?? null,
            ]
        ]);

        return Cart::session($userToken)->condition([$deliveryFees]);
    }

    public function minimumOrderAmountCondition($request, $amount = null, $userToken = null)
    {
        $amountCondition = new CartCondition([
            'name' => $this->minOrderAmountCondition,
            'type' => $this->minOrderAmountCondition,
            'target' => 'total',
            'value' => (string)0,
            'attributes' => [
                'amount' => $amount,
                'state_id' => $request->state_id ?? null,
                'branch_id' => $request->vendor_id ?? null,
            ]
        ]);
        return Cart::session($userToken)->condition([$amountCondition]);
    }

    public function deleteProductFromCart($productId)
    {
        if (auth()->check())
            $userToken = auth()->user()->id;
        else
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY'));

        if ($userToken) {
            $cartItem = Cart::session($userToken)->remove($productId);

            if (count(getCartContent()) <= 0)
                return $this->clearCart();

            return $cartItem;
        }
        return false;
    }

    public function clearCart()
    {
        if (auth()->check())
            $userToken = auth()->user()->id;
        else
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY'));

        if ($userToken) {
            Cart::session($userToken)->clear();
            Cart::session($userToken)->clearCartConditions();
            return true;
        } else {
            return false;
        }
    }

    function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $key;
            }
        }
        return null;
    }

    public function getAddonsOptionsTotalAmount($addOnOptions)
    {
        $priceObject = [];
        $total = 0;
        $index = 0;
        foreach ($addOnOptions as $k => $items) {
            if (isset($items->options) && !empty($items->options)) {
                foreach ($items->options as $i => $item) {
                    $price = AddonOption::find($item)->price;
                    $priceObject[$index]['id'] = $item;
                    $priceObject[$index]['amount'] = number_format($price, 3);
                    $total += floatval($price);
                    $index++;
                }
            }
        }
        return [
            'total' => $total,
            'addonsPriceObject' => $priceObject,
        ];
    }

    public function getCartUserToken()
    {
        if (auth()->check())
            $userToken = auth()->user()->id;
        else
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY'));

        return $userToken;
    }

    public function getCartConditionObject($userToken)
    {
        return DatabaseStorageModel::where('id', $userToken . '_cart_conditions')->first();
    }

    public function getCartItemsObject($userToken)
    {
        return DatabaseStorageModel::where('id', $userToken . '_cart_items')->first();
    }

    public function updateCartKey($userToken, $newUserId)
    {
        $oldCartConditions = $this->getCartConditionObject($newUserId);
        $oldCartItems = $this->getCartItemsObject($newUserId);
        $currentCartConditions = $this->getCartConditionObject($userToken);
        $currentCartItems = $this->getCartItemsObject($userToken);

        if ($oldCartConditions)
            $oldCartConditions->delete();

        if ($oldCartItems)
            $oldCartItems->delete();

        if ($currentCartConditions)
            $currentCartConditions->update(['id' => $newUserId . '_cart_conditions']);

        if ($currentCartItems)
            $currentCartItems->update(['id' => $newUserId . '_cart_items']);

        return true;
    }

    public function removeCartConditionByType($type = '', $userToken = null)
    {
        $userCartToken = $userToken ?? $this->getCartUserToken();
        Cart::session($userCartToken)->removeConditionsByType($type);
        return true;
    }
}

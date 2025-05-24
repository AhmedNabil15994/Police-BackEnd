<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Entities\ProductAddon;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Catalog\Http\Requests\FrontEnd\CartRequest;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;

class ShoppingCartController extends Controller
{
    use ShoppingCartTrait, CatalogTrait;

    protected $product;
    protected $vendor;

    function __construct(Product $product, Vendor $vendor)
    {
        $this->product = $product;
        $this->vendor = $vendor;
    }

    public function index()
    {
        $items = getCartContent();
        return view('catalog::frontend.shopping-cart.index', compact('items'));
    }

    public function totalCart()
    {
        return number_format(getCartSubTotal(), 3);
    }

    public function headerCart()
    {
        return view('apps::frontend.layouts._cart');
    }

    public function createOrUpdate(CartRequest $request, $productSlug, $variantPrdId = null)
    {
        $data = [];
        if (isset($request->product_type) && $request->product_type == 'variation') {
            $product = $this->product->findVariantProductById($variantPrdId);
            if (!$product)
                return response()->json(["errors" => __('catalog::frontend.products.alerts.product_not_available')], 401);

            $product->product_type = 'variation';
            $selectedVendor = $this->vendor->findById($request->vendor_id);
            $product->vendor = $selectedVendor;
            $routeParams = [$product->product->translate(locale())->slug, generateVariantProductData($product->product, $variantPrdId, json_decode($request->selectedOptionsValue))['slug']];
            $data['productDetailsRoute'] = route('frontend.products.index', $routeParams);
            $data['productTitle'] = generateVariantProductData($product->product, $variantPrdId, json_decode($request->selectedOptionsValue))['name'];
            $productCartId = 'var-' . $product->id;
        } else {
            $product = $this->product->findBySlug($productSlug);
            if (!$product)
                return response()->json(["errors" => __('catalog::frontend.products.alerts.product_not_available')], 401);

            $product->product_type = 'product';
            $selectedVendor = $this->vendor->findById($request->vendor_id);
            $product->vendor = $selectedVendor;
            $data['productDetailsRoute'] = route('frontend.products.index', [$product->translate(locale())->slug]);
            $data['productTitle'] = $product->translate(locale())->title;
            $productCartId = $product->id;

            ### Start - Check Single Addons Selections - Validation ###
            if ($request->request_type == 'product') {
                $addonsOptions = isset($request->addonsOptions) ? json_decode($request->addonsOptions) : [];
                $addOnsCheck = $this->checkProductAddonsValidation($addonsOptions, $product);
                if (gettype($addOnsCheck) == 'string')
                    return response()->json(["errors" => $addOnsCheck], 401);
            }
            ### End - Check Single Addons Selections - Validation ###

            if (count($product->variants) > 0) {
                return response()->json(["errors" => __('catalog::frontend.cart.product_have_variations_it_cannot_be_ordered')], 401);
            }
        }

        /*if (!$product)
            abort(404);*/

        $addonsValidationRes = $this->addonsValidation($request, $product->id);
        if (gettype($addonsValidationRes) == 'string')
            return response()->json(["errors" => $addonsValidationRes], 401);

        $checkProduct = is_null(getCartItemById($productCartId));
        if (isset($request->request_type) && $request->request_type == 'general_cart') {
            $request->merge(['qty' => getCartItemById($productCartId) ? getCartItemById($productCartId)->quantity + 1 : 1]);
        }

        #### Start - Check Delivery Charge ####
        $userToken = $this->getUserCartToken();
        /*$currentShippingBranch = !is_null(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH'))) ? json_decode(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH'))) : null;
        $deliveryShippingBranch = !is_null(getCartConditionByName(null, 'company_delivery_fees')) ? getCartConditionByName($userToken, 'company_delivery_fees')->getAttributes() : null;
        if (!is_null($deliveryShippingBranch) && ($currentShippingBranch->vendor_id != $deliveryShippingBranch['branch_id'] || ($currentShippingBranch->vendor_id == $deliveryShippingBranch['branch_id'] && $currentShippingBranch->state_id != $deliveryShippingBranch['state_id']))) {
            return response()->json(["errors" => __('catalog::frontend.products.alerts.empty_cart_firstly'), 'itemQty' => intval($request->qty) - 1], 401);
        }

        if (is_null($currentShippingBranch) || $currentShippingBranch->state_id == null)
            return response()->json(["errors" => __('apps::frontend.master.choose_shipping_area')], 401);*/


        $currentShippingBranch = checkPickupDeliveryCookie();
        if (optional(optional($currentShippingBranch)->content)->vendor_id == null)
            return response()->json(["errors" => __('apps::frontend.master.choose_shipping_branches')], 401);

        /*$deliveryShippingBranch = !is_null(getCartConditionByName(null, 'company_delivery_fees')) ? getCartConditionByName($userToken, 'company_delivery_fees')->getAttributes() : null;
        if (!is_null($deliveryShippingBranch) && (optional(optional($currentShippingBranch)->content)->vendor_id != $deliveryShippingBranch['branch_id'])) {
            return response()->json(["errors" => __('catalog::frontend.products.alerts.empty_cart_firstly'), 'itemQty' => intval($request->qty) - 1], 401);
        }*/

        // check if old and new branch are different
        if (getCartContent()->count() > 0 && !empty($request->vendor_id) && $request->vendor_id != (getCartContent()->first()->attributes['vendor_id'] ?? ''))
            return response()->json(["errors" => __('catalog::frontend.products.alerts.empty_cart_firstly'), 'itemQty' => intval($request->qty) - 1], 401);

        #### End - Check Delivery Charge ####
        $request->request->add(['vendor_id' => optional(optional($currentShippingBranch)->content)->vendor_id, 'state_id' => optional(optional($currentShippingBranch)->content)->state_id]);

        $errors = $this->addOrUpdateCart($product, $request);
        if ($errors)
            return response()->json(["errors" => $errors], 401);

        /* Start - Saving Delivery Charge */

        $this->saveAndRemoveDeliveryCharge($request, $userToken);
        $this->saveMinOrderAmountCondition($request, $userToken);
        /* End - Saving Delivery Charge */

        $data["total"] = number_format(getCartTotal(), 3);
        $data["subTotal"] = number_format(getCartSubTotal(), 3);
        $data["cartCount"] = count(getCartContent());
        $data["productPrice"] = $product->offer ? $product->offer->offer_price : $product->price;
        $data["productQuantity"] = optional(getCartItemById($productCartId)->quantity ?? null);
        $data["product_type"] = $request->product_type ?? '';

        if ($checkProduct) {
            return response()->json(["message" => __('catalog::frontend.cart.add_successfully'), "data" => $data], 200);
        } else {
            return response()->json(["message" => __('catalog::frontend.cart.updated_successfully'), "data" => $data], 200);
        }
    }

    public function delete(Request $request, $id)
    {
        if ($request->product_type == 'product')
            $deleted = $this->deleteProductFromCart($id);
        else
            $deleted = $this->deleteProductFromCart('var-' . $id);

        if ($deleted == true)
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.delete_item')]);

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    public function deleteByAjax(Request $request)
    {
        if ($request->product_type == 'product')
            $deleted = $this->deleteProductFromCart($request->id);
        else
            $deleted = $this->deleteProductFromCart('var-' . $request->id);

        if ($deleted == true) {
            $result["cartCount"] = count(getCartContent());
            $result["cartTotal"] = number_format(getCartSubTotal(), 3);
            return response()->json(["message" => __('catalog::frontend.cart.delete_item'), "result" => $result], 200);
        }

        return response()->json(["errors" => __('catalog::frontend.cart.error_in_cart')], 401);
    }

    public function clear(Request $request)
    {
        $cleared = $this->clearCart();

        if ($cleared)
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.clear_cart')]);

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    public function addonsValidation($request, $productId)
    {
        $request->addonsOptions = isset($request->addonsOptions) ? json_decode($request->addonsOptions) : [];
        if (isset($request->addonsOptions) && !empty($request->addonsOptions) && $request->product_type == 'product') {
            foreach ($request->addonsOptions as $k => $value) {

                $addOns = ProductAddon::where('product_id', $productId)->where('addon_category_id', $value->id)->first();
                if (!$addOns) {
                    return __('cart::api.validations.addons.addons_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->id;
                }

                $optionsIds = $addOns->addonOptions ? $addOns->addonOptions->pluck('addon_option_id')->toArray() : [];
                if ($addOns->type == 'single' && count($value->options) > 0 && !in_array($value->options[0], $optionsIds)) {
                    return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->options[0];
                }

                if ($addOns->type == 'multi') {
                    if ($addOns->max_options_count != null && count($value->options) > intval($addOns->max_options_count)) {
                        return __('cart::api.validations.addons.selected_options_greater_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if ($addOns->min_options_count != null && count($value->options) < intval($addOns->min_options_count)) {
                        return __('cart::api.validations.addons.selected_options_less_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if (count($value->options) > 0) {
                        foreach ($value->options as $i => $item) {
                            if (!in_array($item, $optionsIds)) {
                                return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $item;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }
}

<?php

namespace Modules\Cart\Http\Controllers\WebService;

use Cart;
use Modules\Cart\Http\Requests\WebService\CreateOrUpdateCartRequest;
use Modules\Cart\Traits\CartTrait;
use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Cart\Transformers\WebService\CartResource;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Product;
use Modules\Catalog\Transformers\WebService\SimpleProductResource;
use Modules\Company\Repositories\WebService\CompanyRepository as CompanyRepo;
use Modules\Cart\Http\Requests\WebService\CompanyDeliveryFeesConditionRequest;
use Modules\User\Repositories\WebService\AddressRepository as AddressRepo;
use Modules\Vendor\Entities\VendorDeliveryCharge;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;

class CartController extends WebServiceController
{
    use CartTrait;

    protected $product;
    protected $company;
    protected $userAddress;
    protected $vendor;

    function __construct(Product $product, CompanyRepo $company, AddressRepo $userAddress, Vendor $vendor)
    {
        $this->product = $product;
        $this->company = $company;
        $this->userAddress = $userAddress;
        $this->vendor = $vendor;
    }

    public function index(Request $request)
    {
        if (auth('api')->check())
            $request->user_token = auth('api')->user()->id;
        else
            $request->user_token = $request->user_token ?? null;

        if (is_null($request->user_token))
            return $this->error(__('apps::frontend.general.user_token_not_found'), [], 422);

        if ($request->with_cart_count_total_only == 'yes') {
            $res = $this->returnCustomResponse($request);
            unset($res['conditions']);
            $result = $res;
        } else
            $result = $this->responseData($request);

        return $this->response($result);
    }

    public function createOrUpdate(CreateOrUpdateCartRequest $request)
    {
        if (auth('api')->check())
            $request->user_token = auth('api')->user()->id;
        else
            $request->user_token = $request->user_token ?? null;

        $result = $this->findProduct($request);
        if (gettype($result) == 'string')
            return $this->error($result, [], 422);

        // check if old and new branch are different
        if ($this->cartCount($request->user_token) > 0 && !empty($request->branch_id) && $request->branch_id != ($this->cartDetails($request->user_token)->first()->attributes['vendor_id'] ?? ''))
            return $this->error(__('catalog::frontend.products.alerts.empty_cart_firstly'), [], 422);

        // if product not exist - save delivery charge
        $isNewProduct = false;
        if ($request->pickup_delivery_type == 'delivery' && is_null(getCartItemById($result['product']->id, $request->user_token))) {
            $isNewProduct = true;
            $deliveryPrice = $this->getDeliveryPrice($request->state_id, $request->branch_id);
            if (is_null($deliveryPrice))
                return $this->error(__('catalog::frontend.checkout.validation.state_not_supported_by_company'), [], 422);
        }

        $res = $this->addOrUpdateCart($result['product'], $result['request']);
        if (gettype($res) == 'string')
            return $this->error($res, [], 422);

        if ($isNewProduct == true) {
            #### Start - Save Delivery Charge ####
            $this->saveAndRemoveDeliveryCharge($request);
            #### End - Save Delivery Charge ####
            #### Start - Save Min Order Amount ####
            $this->saveMinOrderAmountCondition($request);
            #### End - Save Min Order Amount ####
        }

        if ($request->pickup_delivery_type == 'pickup')
            $this->removeConditionByName($request, 'company_delivery_fees');

        return $this->response($this->responseData($request));
    }

    public function remove(Request $request, $id)
    {
        $this->removeItem($request, $id);
        return $this->response($this->responseData($request));
    }

    public function saveAndRemoveDeliveryCharge($request)
    {
        $price = $this->getDeliveryPrice($request->state_id, $request->branch_id);
        if ($price) {
            $this->removeConditionByName($request, 'company_delivery_fees');
            $this->companyDeliveryChargeCondition($request, floatval($price));
        }
        return $price;
    }

    public function saveMinOrderAmountCondition($request)
    {
        $amount = VendorDeliveryCharge::where('state_id', $request->state_id)->where('vendor_id', $request->branch_id)->value('min_order_amount');
        $this->removeConditionByName($request, 'min_order_amount');
        $this->minimumOrderAmountCondition($request, $amount ? number_format($amount, 3) : null);
        return $amount;
    }

    public function addCompanyDeliveryFeesCondition(CompanyDeliveryFeesConditionRequest $request)
    {
        /*if (getCartSubTotal($request->user_token) <= 0)
            return $this->error(__('coupon::api.coupons.validation.cart_is_empty'), [], 422);*/

        if (auth('api')->check()) {
            // Get user address and state by address_id
            $address = $this->userAddress->findById($request->address_id);
            if (!$address)
                return $this->error(__('user::webservice.address.errors.address_not_found'));

            $request->request->add(['state_id' => $address->state_id]);
        }

        $companyId = config('setting.other.shipping_company') ?? 0;
        $price = $this->company->getDeliveryPrice($request->state_id, $companyId);

        if ($price) {
            $this->removeConditionByName($request, 'company_delivery_fees');
            $this->companyDeliveryChargeCondition($request, floatval($price));
        } else {
            $this->removeConditionByName($request, 'company_delivery_fees');
            return $this->error(__('catalog::frontend.checkout.validation.state_not_supported_by_company'), [], 422);
        }

        $result = $this->returnCustomResponse($request);
        return $this->response($result);
    }

    public function removeCondition(Request $request, $name)
    {
        $this->removeConditionByName($request, $name);
        return $this->response($this->responseData($request));
    }

    public function clear(Request $request)
    {
        $this->clearCart($request->user_token);
        return $this->response($this->responseData($request));
    }

    public function responseData($request)
    {
        $collections = collect($this->cartDetails($request->user_token));
        $data = $this->returnCustomResponse($request);
        $data['items'] = CartResource::collection($collections);

        if (!is_null(getCartItemsCouponValue($request->user_token)) && getCartItemsCouponValue($request->user_token) > 0) {
            $data['coupon_value'] = number_format(getCartItemsCouponValue($request->user_token), 2);
        } else {
            $couponDiscount = $this->getCondition($request, 'coupon_discount');
            $data['coupon_value'] = !is_null($couponDiscount) ? $couponDiscount->getValue() : null;
        }

        return $data;
    }

    public function findProduct($request)
    {
        $product = null;
        // check if product single OR variable (variant)
        if ($request->product_type == 'product') {
            $product = $this->product->findOneProduct($request->product_id);
            if (!$product)
                return __('cart::api.cart.product.not_found') . $request->product_id;

            ### Start - Check Single Addons Selections - Validation ###
            if (is_null(getCartItemById($request->product_id, $request->user_token))) {
                $addOnsCheck = $this->checkProductAddonsValidation($request->addonsOptions, $product);
                if (gettype($addOnsCheck) == 'string')
                    return $addOnsCheck;
            }
            ### End - Check Single Addons Selections - Validation ###

            $product->product_type = 'product';
        } else {
            $product = $this->product->findOneProductVariant($request->product_id);
            if (!$product)
                return __('cart::api.cart.product.not_found') . $request->product_id;

            $product->product_type = 'variation';

            // Get variant product options and values
            $options = [];
            foreach ($product->productValues as $k => $value) {
                $options[] = $value->productOption->option->id;
            }
            $selectedOptionsValue = $product->productValues->pluck('option_value_id')->toArray();

            // Append options and options values to current request
            // - encode data to match frontend scenario
            $request->request->add([
                'selectedOptions' => json_encode($options),
                'selectedOptionsValue' => json_encode($selectedOptionsValue),
            ]);

            /*if (!isset($request->selectedOptions) || empty($request->selectedOptions)) {
                $error = 'Please, Enter Selected Options';
                return $this->error($error, [], 422);
            }

            if (!isset($request->selectedOptionsValue) || empty($request->selectedOptionsValue)) {
                $error = 'Please, Enter Selected Options Values';
                return $this->error($error, [], 422);
            }*/
        }

        $selectedVendor = $this->vendor->findById($request->branch_id);
        $product->vendor = $selectedVendor ?? null;

        return [
            'product' => $product,
            'request' => $request,
        ];
    }

    protected function getDeliveryPrice($sateId, $branchId)
    {
        return VendorDeliveryCharge::where('state_id', $sateId)->where('vendor_id', $branchId)->value('delivery');
    }

    protected function returnCustomResponse($request)
    {
        return [
            'conditions' => $this->getCartConditions($request),
            'subTotal' => number_format($this->cartSubTotal($request), 3),
            'total' => number_format($this->cartTotal($request), 3),
            'count' => $this->cartCount($request->user_token),
        ];
    }
}

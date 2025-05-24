<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Catalog\Http\Requests\FrontEnd\CheckoutInformationRequest;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Catalog\Http\Requests\FrontEnd\CheckoutLimitationRequest;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Vendor\Entities\VendorDeliveryCharge;
use Modules\Vendor\Repositories\FrontEnd\PaymentRepository as PaymentMethods;
use Modules\Company\Repositories\FrontEnd\CompanyRepository as Company;
use Modules\Area\Repositories\FrontEnd\StateRepository as State;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\Dashboard\StateVendorsResource;
use Spatie\ResponseCache\Facades\ResponseCache;

class CheckoutController extends Controller
{
    use ShoppingCartTrait, CatalogTrait, VendorTrait;

    protected $product;
    protected $payment;
    protected $company;
    protected $state;
    protected $vendor;

    function __construct(Product $product, PaymentMethods $payment, Company $company, State $state, Vendor $vendor)
    {
        $this->product = $product;
        $this->payment = $payment;
        $this->company = $company;
        $this->state = $state;
        $this->vendor = $vendor;
    }

    public function index(Request $request)
    {
        $paymentMethods = $this->payment->getAll();
        $state = null;
        if (!is_null(checkPickupDeliveryCookie())) {
            $selectedStateId = optional(optional(checkPickupDeliveryCookie())->content)->state_id;
            $state = $this->state->findById($selectedStateId);

            if (checkPickupDeliveryCookie()->type == 'delivery' && is_null($state))
                return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.checkout.validation.please_choose_state')]);

        }
        return view('catalog::frontend.checkout.index', compact('paymentMethods', 'state'));
    }

    public function saveCheckoutInformation(CheckoutInformationRequest $request)
    {
        // add cart conditions
        dd($request->all());
    }

    public function getContactInfo(Request $request)
    {
        $savedContactInfo = !empty(get_cookie_value(config('core.config.constants.CONTACT_INFO'))) ? (array)\GuzzleHttp\json_decode(get_cookie_value(config('core.config.constants.CONTACT_INFO'))) : [];
        return view('catalog::frontend.checkout.index', compact('savedContactInfo'));
    }

    public function getPaymentMethods(Request $request)
    {
        $cartAttributes = isset(Cart::getConditions()['delivery_fees']) && !empty(Cart::getConditions()['delivery_fees']) ? Cart::getConditions()['delivery_fees']->getAttributes() : null;

        if ($cartAttributes && $cartAttributes['address'] != null) {

            $address = Cart::getCondition('delivery_fees')->getAttributes()['address'];
            $vendor = \Modules\Vendor\Entities\Vendor::find(Cart::getCondition('vendor')->getType());

            return view('catalog::frontend.checkout.index', compact('address', 'vendor'));
        } else {
            return redirect()->back();
        }
    }

    public function getStateDeliveryPrice(Request $request)
    {
        $userToken = $this->getUserCartToken();
        if ($request->state_id && $request->vendor_id)
            $branch = $this->vendor->getBranchByIdAndState($request->state_id, $request->vendor_id);
        else
            $branch = null;

        $response = [
            'success' => true,
            'data' => $branch ? new StateVendorsResource($branch) : null,
            'delivery' => $this->saveAndRemoveDeliveryCharge($request, $userToken),
            'sub_total' => number_format(getCartSubTotal(), 3),
            'total' => number_format(getCartTotal(), 3),
        ];
        ResponseCache::clear();
        return response()->json($response);
    }

}

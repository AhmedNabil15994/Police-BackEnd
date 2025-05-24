<?php

namespace Modules\Vendor\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Vendor\Http\Requests\FrontEnd\AskQuestionRequest;
use Modules\Vendor\Http\Requests\FrontEnd\PrescriptionRequest;
use Modules\Vendor\Http\Requests\WebService\RateRequest;
use Modules\Vendor\Traits\UploaderTrait;
use Modules\Vendor\Transformers\WebService\DeliveryCompaniesResource;
use Modules\Vendor\Transformers\WebService\SectionResource;
use Modules\Vendor\Transformers\WebService\VendorResource;
use Modules\Vendor\Transformers\WebService\DeliveryChargeResource;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Modules\Vendor\Repositories\Vendor\RateRepository as Rate;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Vendor\Notifications\FrontEnd\AskVendordNotification;
use Modules\Vendor\Notifications\FrontEnd\PrescriptionVendordNotification;
use Notification;

class VendorController extends WebServiceController
{
    use UploaderTrait;
    protected $vendor;
    protected $rate;

    function __construct(Vendor $vendor, Rate $rate)
    {
        $this->vendor = $vendor;
        $this->rate = $rate;
    }

    public function sections()
    {
        $sections = $this->vendor->getAllSections();

        return SectionResource::collection($sections);
    }

    public function vendors(Request $request)
    {
        $vendors = $this->vendor->getAllVendors($request);

        return VendorResource::collection($vendors);
    }

    public function getVendorById(Request $request)
    {
        $vendor = $this->vendor->getOneVendor($request);

        return new VendorResource($vendor);
    }

    public function deliveryCharge(Request $request)
    {
        $charge = $this->vendor->getDeliveryChargesByVendorByState($request);

        if (!$charge)
            return $this->response([]);

        return $this->response(new DeliveryChargeResource($charge));
    }

    public function vendorRate(RateRequest $request)
    {
        $order = $this->rate->findOrderByIdWithUserId($request->order_id);
        if ($order) {
            $rate = $this->rate->checkUserRate($request->order_id);
            if (!$rate) {
                $request->merge([
                    'vendor_id' => $order->vendor_id,
                ]);
                $createdRate = $this->rate->create($request);
                return $this->response([]);
            } else
                return $this->error(__('vendor::webservice.rates.user_rate_before'));
        } else
            return $this->error(__('vendor::webservice.rates.user_not_have_order'));
    }

    public function getVendorDeliveryCompanies(Request $request, $id)
    {
        $vendor = $this->vendor->findVendorByIdAndStateId($id, $request->state_id);
        if ($vendor) {
            $result['companies'] = DeliveryCompaniesResource::collection($vendor->companies);
            $result['vendor_fixed_delivery'] = $vendor->fixed_delivery;
            return $this->response($result);
        } else {
            return $this->error(__('vendor::webservice.companies.vendor_not_found_with_this_state'), null);
        }
    }

    public function getPickupVendors(Request $request)
    {
        $vendors = $this->vendor->getBranchesByRestaurantIdAndPickup(app('defaultRestaurant')->id ?? null);
        return VendorResource::collection($vendors);
    }


}

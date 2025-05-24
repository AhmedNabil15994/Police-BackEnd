<?php

namespace Modules\Vendor\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Vendor\Traits\VendorTrait;

class VendorResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => url($this->image),
            'title' => $this->translate(locale())->title,
            'description' => $this->translate(locale())->description,
            /*'fixed_delivery' => $this->fixed_delivery,
            'order_limit' => $this->order_limit,
            'rate' => $this->getVendorTotalRate($this->rates),
            'payments' => PaymenteResource::collection($this->payments),
            'opening_status' => new OpeningStatusResource($this->openingStatus),*/
        ];

        if (request()->route()->getName() == 'get_one_vendor')
            $result['areas'] = (count($this->deliveryCharge) > 0) ? DeliveryChargeResource::collection($this->deliveryCharge) : null;
        else {
            if (request()->route()->getName() != 'api.vendors.supported_pickup_branches')
                $result['delivery_charge'] = (count($this->deliveryCharge) > 0) ? new DeliveryChargeResource($this->deliveryCharge[0]) : null;
        }

        return $result;
    }
}

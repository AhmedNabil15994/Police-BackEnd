<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\WebService\VendorResource;

class OrderResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        $result = [
            'id' => $this->id,
            'order_tracking_id' => encryptOrderId($this->id),
            'total' => number_format($this->total, 3),
            'shipping' => $this->shipping,
            'subtotal' => number_format($this->subtotal, 3),
            'transaction' => $this->transactions->method,
            'order_status' => [
                'code' => $this->orderStatus->code,
                'title' => $this->orderStatus->translate(locale())->title,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'notes' => $this->notes,
            'products' => OrderProductResource::collection($allOrderProducts),
        ];

        $result['address'] = new OrderAddressResource($this->orderAddress);
        if($this->locationHistory || (isset($this->driver) && $this->driver->driver->locationHistory) ){
            $resource = $this->locationHistory()->orderBy('id','DESC')->first() ?? (isset($this->driver) ? $this->driver->driver->locationHistory()->orderBy('id','DESC')->first() : null);
            $result['last_location'] = new OrderLocationHistoryResource($resource);
        }

        /*if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }*/

        return $result;
    }
}

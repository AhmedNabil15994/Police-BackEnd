<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\WebService\VendorResource;

class OrderWebhookResource extends Resource
{
    use VendorTrait;
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this->pickup_delivery) && isset($this->pickup_delivery['branch_id'])){
            $branch = getBranchById($this->pickup_delivery['branch_id']);
        }else{
            $branch = isset($this->vendors) && $this->vendors && !empty($this->vendors[0]) ? $this->vendors[0] : null;
        }
        $vendor = new VendorResource($branch);

        $allOrderProducts = isset($this->orderProducts) ? $this->orderProducts->mergeRecursive($this->orderVariations) : null;
        $result = [
            'id' => $this->id,
            'order_tracking_id' => encryptOrderId($this->id),
            'total' => number_format($this->total, 3),
            'shipping' => $this->shipping,
            'subtotal' => number_format($this->subtotal, 3),
            'transaction' => $this->transactions->method,
            'shipping_time' => [
                'time' => json_decode($this->shipping_time)->shipping_time ?? '',
                'hour' => json_decode($this->shipping_time)->shipping_hour ?? '',
                'day' => json_decode($this->shipping_time)->shipping_day ?? '',
            ],
            'pickup_delivery' => [
                'pickup_delivery_type'  => $this->pickup_delivery['pickup_delivery_type'] ?? ''
            ],
            'order_status' => [
                'code' => $this->orderStatus->code,
                'title' => $this->orderStatus->translate(locale())->title,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'notes' => $this->notes,
            'products' => OrderProductWebhookResource::collection($allOrderProducts),
            'store' => $vendor,
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
        ];

        $result['address'] = new OrderAddressResource($this->orderAddress);

        /*if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }*/

        return $result;
    }
}

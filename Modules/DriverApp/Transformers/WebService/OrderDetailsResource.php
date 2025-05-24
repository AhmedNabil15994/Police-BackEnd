<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Traits\CoreTrait;
use Modules\Order\Entities\PaymentType;
use Modules\User\Transformers\WebService\UserResource;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\WebService\VendorResource;

class OrderDetailsResource extends JsonResource
{
    use VendorTrait;
    use CoreTrait;

    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        $paymentTypeObj = PaymentType::whereFlag($this->transactions->method)->first();
        $paymentStatusFlag = optional($this->paymentStatus)->flag;
        $result = [
            'id' => $this->id,
            'total' => $this->total,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'transaction' => $this->transactions->method,
            'order_status' => [
                'title' => optional($this->orderStatus)->title,
                'image' => optional($this->orderStatus)->image ? url($this->orderStatus->image) : url(config('setting.images.logo')),
                'flag' => optional($this->orderStatus)->flag,
                'is_success' => optional($this->orderStatus)->is_success,
                'sort' => optional($this->orderStatus)->sort,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'notes' => $this->notes,
            'products' => OrderProductResource::collection($allOrderProducts),
            'payment_type'  =>  $paymentTypeObj->title ?? null,
            'payment_status' => $paymentStatusFlag ? trans('apps::dashboard.payment_statuses.'.$paymentStatusFlag) : null,
            'delivery_time' => $this->delivery_time ?? null,
            'vendor'   =>  VendorResource::collection($this->vendors)[0],
        ];

        if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }



        if (!is_null($this->user)) {
            $result['user'] = new UserResource($this->user);
        } else {
            $result['user'] = null;
        }

        if (!is_null($this->driver)) {
            $result['driver'] = new OrderDriverResource($this->driver);
        } else {
            $result['driver'] = null;
        }

        return $result;
    }
}

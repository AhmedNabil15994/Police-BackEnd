<?php

namespace Modules\Order\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'order_tracking_id' => encryptOrderId($this->id),
            'unread' => $this->unread,
            'client' => $this->user ? $this->user->name : '',
            'mobile'    => $this->user ? $this->user->calling_code. $this->user->mobile : '',
            'total' => $this->total,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'transaction' => $this->transactions->method,
            'order_status_id' => optional(optional($this->orderStatus)->translate(locale()))->title,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];

        if (!is_null($this->pickup_delivery)) {
            if ($this->pickup_delivery['pickup_delivery_type'] == 'delivery') {
                $result['pickup_delivery'] = __('order::dashboard.orders.messages.delivery');
            } elseif ($this->pickup_delivery['pickup_delivery_type'] == 'pickup') {
                $result['pickup_delivery'] = __('order::dashboard.orders.messages.pickup');
            } else {
                $result['pickup_delivery'] = '---';
            }
        } else {
            $result['pickup_delivery'] = '---';
        }

        return $result;
    }
}

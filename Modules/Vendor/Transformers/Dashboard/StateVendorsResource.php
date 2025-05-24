<?php

namespace Modules\Vendor\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class StateVendorsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->translate(locale())->title,
            'min_order_amount' => $this->deliveryCharge ? $this->deliveryCharge[0]->min_order_amount : null,
            'delivery' => $this->deliveryCharge ? $this->deliveryCharge[0]->delivery : null,
        ];
    }
}

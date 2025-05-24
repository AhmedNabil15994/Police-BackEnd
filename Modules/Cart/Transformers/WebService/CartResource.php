<?php

namespace Modules\Cart\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'qty' => $this->quantity,
            'title' => $this->attributes->product->translate(locale())->title,
            'image' => url($this->attributes->product->image),
            'product_type' => $this->attributes->product->product_type,
            'notes' => $this->attributes->notes,
            'vendor_id' => $this->attributes->vendor_id,
        ];

        if ($this->attributes->addonsOptions) {
            $price = floatval($this->price) - floatval($this->attributes->addonsOptions['total_amount']);
            $result['price'] = number_format($price, 3);
        } else
            $result['price'] = number_format($this->price, 3);

        $result['addons'] = $this->attributes->addonsOptions;
        return $result;
    }
}

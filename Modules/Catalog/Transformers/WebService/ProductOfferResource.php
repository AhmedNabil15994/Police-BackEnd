<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'offer_price' => number_format($this->offer_price, 3),
        ];
    }
}

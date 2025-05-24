<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsV2Resource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'price' => is_null($this->price) ? 0 : $this->price,
            'qty' => $this->qty,
            'image' => $this->image ? url($this->image) : null,
            'title' => $this->translate(locale())->title,
            'short_description' => $this->translate(locale())->short_description,
            'offer' => new ProductOfferResource($this->offer),
        ];

        if (auth('api')->check()) {
            $result['is_favorite'] = CheckProductInUserFavourites($this->id, auth('api')->id());
        } else {
            $result['is_favorite'] = null;
        }
        
        return $result;
    }
}

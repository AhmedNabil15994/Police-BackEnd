<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Advertising\Transformers\WebService\AdvertisingResource;
use Modules\Tags\Transformers\WebService\TagsResource;
use Modules\Vendor\Transformers\WebService\BranchesResource;
use Modules\Vendor\Transformers\WebService\OpeningStatusResource;

class SimpleProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => number_format($this->price, 3),
            'qty' => $this->qty,
            'image' => url($this->image),
            'title' => $this->translate(locale())->title,
            'sharable_link' => route('frontend.products.index', optional($this->translate(locale()))->slug),
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

<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Advertising\Transformers\WebService\AdvertisingResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => optional($this->translate(locale()))->title,
            'image' => url($this->image),
            'color' => $this->color ?? null,
            // 'products_count' => $this->products_count ?? 0,
            // 'description' => htmlView($this->translate(locale())->description),
        ];

        if (request()->route()->getName() != 'api.home') {
            $result['products'] = ProductResource::collection($this->products);
            /*$result['products'] = ProductResource::collection($this->products->take(10));
            $result['sub_categories'] = CategoryResource::collection($this->childrenRecursive);*/
        }

        $result['adverts'] = AdvertisingResource::collection($this->adverts);
        return $result;
    }
}

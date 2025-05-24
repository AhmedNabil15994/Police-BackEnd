<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResourceV2 extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => optional($this->translate(locale()))->title,
            'image' => $this->image ? url($this->image) : null,
            'color' => $this->color ?? null,
            'products' => ProductDetailsV2Resource::collection($this->products),
        ];
        return $result;
    }
}

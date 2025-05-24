<?php

namespace Modules\Supplier\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => url($this->image),
        ];
    }
}

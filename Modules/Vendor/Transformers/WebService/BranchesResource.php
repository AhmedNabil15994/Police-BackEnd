<?php

namespace Modules\Vendor\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Vendor\Traits\VendorTrait;

class BranchesResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => url($this->image),
            'title' => $this->translate(locale())->title,
            'description' => $this->translate(locale())->description ? cleanText($this->translate(locale())->description) : null,
            'fixed_delivery' => $this->fixed_delivery,
            'order_limit' => $this->order_limit,
            'opening_status' => new OpeningStatusResource($this->openingStatus),
            'restaurant' => new RestaurantOfBranchResource($this->branchRestaurant),
        ];
        return $result;
    }
}

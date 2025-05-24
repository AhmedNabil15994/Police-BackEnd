<?php

namespace Modules\Area\Transformers\WebService;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Vendor\Transformers\WebService\OpeningStatusResource;

class StateBranchesResource extends Resource
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
            'delivery_price' => floatval($this->pivot->delivery),
            'min_order_amount' => floatval($this->pivot->min_order_amount),
            'opening_status' => new OpeningStatusResource($this->openingStatus),
        ];
    }
}

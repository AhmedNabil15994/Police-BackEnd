<?php

namespace Modules\Advertising\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisingGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->translate(locale())->title,
            'sort' => $this->sort,
            'item_style' => $this->item_style,
            'adverts' => AdvertisingResource::collection($this->adverts),
        ];
    }
}

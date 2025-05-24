<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\ProductResource;

class OrderLocationHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'lat' => $this->latitude,
            'long' => $this->longitude,
            'driver'    => $this->driver ? $this->driver->name : '',
            'date' => $this->created_at,
        ];
    }
}

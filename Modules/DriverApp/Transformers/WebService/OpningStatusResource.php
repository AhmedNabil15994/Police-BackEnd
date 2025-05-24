<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;


class OpningStatusResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'  => $this->title ,

        ];
    }
}

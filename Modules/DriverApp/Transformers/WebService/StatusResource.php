<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Vendor\Entities\VendorStatus;


class StatusResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'  => $this->title ,
            'image'  => url($this->image) ,
            'openingStatus' => $this->openingStatus != null ? new OpningStatusResource($this->openingStatus) : new OpningStatusResource(VendorStatus::where('id' , 3)->first())
        ];
    }
}

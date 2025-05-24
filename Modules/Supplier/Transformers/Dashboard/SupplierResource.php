<?php

namespace Modules\Supplier\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class SupplierResource extends Resource
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
            'image' => url($this->image),
            'status' => $this->status,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}

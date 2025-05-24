<?php

namespace Modules\Order\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class OrderStatusHistoryResource extends Resource
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
            'order_status' => $this->orderStatus->translate(locale())->title,
            'user' => !is_null($this->user) ? $this->user->name : '',
            'date' => $this->created_at,
        ];
    }
}

<?php

namespace Modules\DriverApp\Repositories\WebService;

use Modules\Order\Entities\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderStatusRepository
{
    protected $orderStatus;

    function __construct(OrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $query = $this->orderStatus->whereNotIn('flag', ['failed','pending','refund','received']);
        if (auth('api')->user()->can('driver_access') || auth('api')->user()->can('dashboard_access')) {
            $query = $query->whereIn('flag', ['processing', 'delivered', 'on_the_way', 'is_ready']);
        }
        return $query->orderBy($order, $sort)->get();
    }

    public function getAllFinalStatus($order = 'id', $sort = 'desc')
    {
        return $this->orderStatus->finalStatus()->orderBy($order, $sort)->get();
    }
}

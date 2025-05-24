<?php
namespace Modules\DriverApp\Events;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LocationUpdated implements ShouldBroadcast
{
    public $order;
    public $latitude;
    public $longitude;

    public function __construct($order, $latitude, $longitude)
    {
        $this->order = $order;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function broadcastOn()
    {
        return [
            config('core.config.constants.DRIVER_DASHBOARD_CHANNEL'),
            'user_orders_'.(encryptOrderId($this->order->id))
        ];
//        return ['location.updated'];
    }

    public function broadcastAs()
    {
        return 'order_location_updated';
//        return 'LocationUpdated';
    }
}

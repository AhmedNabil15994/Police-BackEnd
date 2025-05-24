<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model
{
    public $timestamps = false;
    protected $fillable = ['order_id', 'vendor_id', 'total_comission', 'total_profit_comission'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\Modules\Vendor\Entities\Vendor::class);
    }

}

<?php

namespace Modules\Vendor\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;

class VendorDeliveryCharge extends Model
{
    use ScopesTrait, ClearsResponseCache;

    protected $fillable = ['delivery', 'delivery_time', 'vendor_id', 'state_id', 'status', 'min_order_amount'];

    public function branch()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

}

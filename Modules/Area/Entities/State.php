<?php

namespace Modules\Area\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;
use Modules\Vendor\Entities\DeliveryCharge;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorDeliveryCharge;

class State extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes, ScopesTrait, ClearsResponseCache;

    protected $with = ['translations'];
    protected $fillable = ['status', 'city_id'];
    public $translatedAttributes = ['title', 'slug'];
    public $translationModel = StateTranslation::class;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /* public function deliveryCharge()
    {
        return $this->hasOne(\Modules\Company\Entities\DeliveryCharge::class, 'state_id');
    } */

    public function deliveryCharge()
    {
        return $this->hasOne(VendorDeliveryCharge::class, 'state_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_states');
    }

    public function branches()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_delivery_charges')
            ->with('openingStatus')
            ->withPivot(['min_order_amount', 'delivery']);
    }

}

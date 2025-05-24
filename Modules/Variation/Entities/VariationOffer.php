<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;

class VariationOffer extends Model
{
    use ScopesTrait, ClearsResponseCache;

    protected $fillable = ['product_variant_id', 'start_at', 'end_at', 'offer_price', 'status', 'percentage'];

    /*public function scopeUnexpired($query)
    {
        return $query->where('start_at', '<=', date('Y-m-d'))->where('end_at', '>', date('Y-m-d'));
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeStarted($query)
    {
        return $query->where('start_at', '<=', date('Y-m-d'));
    }*/

}

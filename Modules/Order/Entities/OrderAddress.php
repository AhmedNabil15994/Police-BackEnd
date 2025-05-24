<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'civil_id', 'email', 'username', 'mobile', 'block',
        'street', 'building', 'address', 'state_id', 'order_id', 'district',
        'governorate', 'floor', 'flat','lat','long'
    ];

    public function state()
    {
        return $this->belongsTo(\Modules\Area\Entities\State::class);
    }
}

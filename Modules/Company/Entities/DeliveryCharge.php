<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;

class DeliveryCharge extends Model
{
    use ClearsResponseCache;

    protected $fillable = ['delivery', 'delivery_time', 'company_id', 'state_id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}

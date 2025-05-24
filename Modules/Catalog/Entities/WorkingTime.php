<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;

class WorkingTime extends Model
{
    use ScopesTrait, ClearsResponseCache;

    protected $table = 'working_times';
    protected $guarded = ['id'];
    protected $casts = [
        "custom_times" => "array",
    ];

    /**
     * Get the parent workingTimeable model (product or other models).
     */
    public function timeable()
    {
        return $this->morphTo();
    }

    public function workingTimeDetails()
    {
        return $this->hasMany(WorkingTimeDetails::class, 'working_time_id');
    }

}

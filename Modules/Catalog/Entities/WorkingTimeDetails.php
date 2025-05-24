<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;

class WorkingTimeDetails extends Model
{
    use ClearsResponseCache;

    protected $table = 'working_time_details';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function workingDay()
    {
        return $this->belongsTo(WorkingTime::class, 'working_time_id');
    }

}

<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderLocationHistory extends Model
{
    protected $fillable = ['order_id','user_id','latitude','longitude'];
    public $table = 'order_location_history';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(\Modules\User\Entities\User::class,'user_id');
    }
}

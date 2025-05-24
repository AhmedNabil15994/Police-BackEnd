<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ClearsResponseCache;

class UserFavourite extends Model
{
    use ClearsResponseCache;

    protected $table = 'users_favourites';
    protected $fillable = [
        'user_id', 'product_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}

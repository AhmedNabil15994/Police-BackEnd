<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;

class ProductImage extends Model
{
    use ClearsResponseCache;

    public $timestamps = false;
    protected $fillable = [
        'product_id', 'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

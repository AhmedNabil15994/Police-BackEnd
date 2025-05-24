<?php

namespace Modules\Vendor\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;

class VendorProduct extends Model
{
    protected $table = 'vendor_products';
    protected $guarded = ['id'];

    public function provider()
    {
        return $this->belongsTo(Vendor::class, 'provider_id');
    }

    public function branch()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}

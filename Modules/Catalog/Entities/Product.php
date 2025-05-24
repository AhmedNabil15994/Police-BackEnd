<?php

namespace Modules\Catalog\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Advertising\Entities\Advertising;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;
use Modules\Order\Entities\OrderProduct;
use Modules\Tags\Entities\Tag;
use Modules\User\Entities\UserFavourite;
use Modules\Variation\Entities\Option;

class Product extends Model
{
    use Translatable, SoftDeletes, ScopesTrait, ClearsResponseCache;

    protected $with = ['translations'];

    protected $fillable = [
        'status', 'featured', 'image', 'price', "sku", "vendor_id", "qty", "shipment", "pending_for_approval",
    ];

    protected $casts = [
        "shipment" => "array"
    ];

    public $translatedAttributes = [
        'title', 'short_description', 'description', 'slug', 'seo_description', 'seo_keywords'
    ];

    public $translationModel = ProductTranslation::class;

    // START - Override active scope to add `pending_for_approval`
    public function scopeActive($query)
    {
        return $query->where('status', true)->where('pending_for_approval', true);
    }

    // END - Override active scope to add `pending_for_approval`

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function subCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNotNull('categories.category_id');
    }

    public function parentCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNull('categories.category_id');
    }

    public function offer()
    {
        return $this->hasOne(ProductOffer::class, 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(\Modules\Vendor\Entities\Vendor::class);
    }

    /* Get Restaurants Of Product */
    public function productProviders()
    {
        return $this->belongsToMany(\Modules\Vendor\Entities\Vendor::class, 'vendor_products', 'product_id', 'provider_id')
            ->withPivot(['vendor_id', /*'price', 'sku', 'qty', 'status'*/])
            ->withTimestamps();
    }

    /* Get Branches Of Product */
    public function productVendors()
    {
        return $this->belongsToMany(\Modules\Vendor\Entities\Vendor::class, 'vendor_products', 'product_id', 'vendor_id')
            ->withPivot(['provider_id', /*'price', 'sku', 'qty', 'status'*/])
            ->with('branchRestaurant')
            ->withTimestamps();
    }

    public function addOns()
    {
        return $this->hasMany(ProductAddon::class, 'product_id');
    }

    // variations
    public function options()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductOption::class);
    }

    public function productOptions()
    {
        return $this->belongsToMany(Option::class, 'product_options');
    }

    public function variants()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantChosed()
    {
        return $this->hasOne(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantValues()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariantValue::class);
    }

    public function checkIfHaveOption($optionId)
    {
        return $this->variantValues->contains('option_value_id', $optionId);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    /**
     * Get all of the product's ordering times.
     */
    public function workingTimes()
    {
        return $this->morphMany(WorkingTime::class, 'timeable');
    }

    public function favourites()
    {
        return $this->hasMany(UserFavourite::class, 'product_id');
    }

    public function adverts()
    {
        return $this->morphMany(Advertising::class, 'advertable');
    }

}

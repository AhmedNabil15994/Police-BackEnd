<?php

namespace Modules\Catalog\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Advertising\Entities\Advertising;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;
use Modules\Occasion\Entities\Occasion;
use Modules\Vendor\Traits\VendorTrait;

class Category extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes, ScopesTrait, VendorTrait, ClearsResponseCache;

    protected $with = ['translations', 'children'];
    // protected $with 					    = ['translations','children','parent','products'];
    protected $fillable = ['status', 'show_in_home', 'image', 'category_id', 'color', 'sort'];
    public $translatedAttributes = ['title', 'slug', 'seo_description', 'seo_keywords'];
    public $translationModel = CategoryTranslation::class;

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function occasions()
    {
        return $this->hasMany(Occasion::class, 'category_id');
    }

    public function children()
    {
        $vendorId = $this->getSingleVendor()->id ?? null;

        $categories = $this->hasMany(Category::class, 'category_id')->withCount(['products' => function ($q) use ($vendorId) {
            $q->active();
            if (!is_null($vendorId)) {
                $q->where('vendor_id', $vendorId);
            }
        }]);

        // Get Child Category Products
        $categories = $categories->with([
            'products' => function ($query) use ($vendorId) {
                $query->active()
                    ->with([
                        'offer' => function ($query) {
                            $query->active()->unexpired()->started();
                        },
                        'options',
                        'images',
                        'vendor',
                        'variants' => function ($q) {
                            $q->with(['offer' => function ($q) {
                                $q->active()->unexpired()->started();
                            }]);
                        },
                    ]);
                if (!is_null($vendorId)) {
                    $query->where('vendor_id', $vendorId);
                }
                $query->orderBy('id', 'DESC')->limit(10);
            },
        ]);

        return $categories;
    }

    public function childrenRecursive()
    {
        return $this->children()->active()->with('childrenRecursive');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id')
            ->has('products')
            ->whereNotNull('categories.category_id');
    }

    public function adverts()
    {
        return $this->morphMany(Advertising::class, 'advertable');
    }

}

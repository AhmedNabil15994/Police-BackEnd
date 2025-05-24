<?php

namespace Modules\Tags\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;

class Tag extends Model
{
    use Translatable, SoftDeletes, ScopesTrait, ClearsResponseCache;

    protected $with = ['translations'];

    protected $fillable = [
        'status', 'color',
    ];

    public $translatedAttributes = [
        'title',
    ];

    public $translationModel = TagTranslation::class;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }

}

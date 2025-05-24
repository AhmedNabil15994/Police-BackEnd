<?php

namespace Modules\Catalog\Entities;

use Modules\Core\Traits\ClearsResponseCache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasSlug, ClearsResponseCache;

    protected $fillable = [
        'title', 'short_description', 'description', 'slug', 'seo_description', 'seo_keywords'
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

<?php

namespace Modules\Vendor\Entities;

use Modules\Core\Traits\ClearsResponseCache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class SectionTranslation extends Model
{
    use HasSlug, ClearsResponseCache;

    protected $fillable = ['description', 'title', 'slug', 'seo_description', 'seo_keywords'];

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

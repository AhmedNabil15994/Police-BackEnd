<?php

namespace Modules\Advertising\Entities;

use Modules\Core\Traits\ClearsResponseCache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class AdvertisingGroupTranslation extends Model
{
    use HasSlug, ClearsResponseCache;

    protected $table = 'advertising_group_translations';
    protected $fillable = [
        'title', 'slug',
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

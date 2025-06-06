<?php

namespace Modules\Tags\Entities;

use Modules\Core\Traits\ClearsResponseCache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    use HasSlug, ClearsResponseCache;

    public $timestamps = false;
    protected $fillable = [
        'title',
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

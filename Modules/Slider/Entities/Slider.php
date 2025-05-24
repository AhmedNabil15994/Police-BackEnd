<?php

namespace Modules\Slider\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;

class Slider extends Model
{
    use Translatable, SoftDeletes, ScopesTrait, ClearsResponseCache;

    protected $with = ['translations'];

    protected $table = 'sliders';
    protected $fillable = ['image', 'link', 'status', 'start_at', 'end_at'];


    public $translatedAttributes = [
        'title', 'short_description', 'slug',
    ];

    public $translationModel = SliderTranslation::class;

    public function scopeStartedAndExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNotNull('start_at');
            $query->whereNotNull('end_at');
            $query->started();
            $query->unexpired();
        })->orWhere(function ($query) {
            $query->whereNotNull('start_at');
            $query->whereNull('end_at');
            $query->started();
        })->orWhere(function ($query) {
            $query->whereNull('start_at');
            $query->whereNull('end_at');
        });
    }

}

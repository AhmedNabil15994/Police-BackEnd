<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ClearsResponseCache;

class OptionTranslation extends Model
{
    use ClearsResponseCache;

    protected $fillable = ['title'];
}

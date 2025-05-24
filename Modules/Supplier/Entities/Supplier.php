<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class Supplier extends Model
{
    use ScopesTrait;

    protected $table = 'suppliers';
    protected $fillable = ['image', 'status'];
}

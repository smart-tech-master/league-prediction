<?php

namespace App\Models\ApiFootball;

use App\Scopes\CurrentSeasonScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $connection = 'api-football';
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new CurrentSeasonScope());
    }
}

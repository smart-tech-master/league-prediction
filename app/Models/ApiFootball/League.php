<?php

namespace App\Models\ApiFootball;

use App\Models\User;
use App\Scopes\SequentialLeagueScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $connection = 'api-football';
    public $timestamps = false;

    public function users(){
        return $this->belongsToMany(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new SequentialLeagueScope());
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function season(){
        return Season::orderByDesc('year')->first();
    }
}

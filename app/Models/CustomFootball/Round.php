<?php

namespace App\Models\CustomFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Scopes\SequentialLeagueScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $connection = 'custom-football';

    public $timestamps = false;

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function fromRound(){
        return $this->belongsTo(\App\Models\ApiFootball\Round::class, 'from');
    }

    public function toRound(){
        return $this->belongsTo(\App\Models\ApiFootball\Round::class, 'to');
    }

    public function competition(){
        return $this->belongsTo(Competition::class);
    }

    public function fixtures(){
        return $this->hasMany(Fixture::class);
    }
}

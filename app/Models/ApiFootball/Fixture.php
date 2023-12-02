<?php

namespace App\Models\ApiFootball;

use App\Models\Prediction;
use App\Scopes\SequentialLeagueScope;
use App\Services\ApiFootball\FixtureService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $connection = 'api-football';
    public $timestamps = false;

    protected $dates = ['timestamp'];

    public function comparison(){
        return FixtureService::comparison($this);
    }

    public function predictions(){
        return $this->setConnection('mysql')->hasMany(Prediction::class);
    }

    public function round(){
        return $this->belongsTo(Round::class);
    }

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function teams(){
        return $this->belongsToMany(Team::class)->withPivot(['ground', 'goals']);
    }

    public function fixturePredictionStatistics(){
        return $this->hasOne(FixturePredictionStatistics::class);
    }

    public function venue(){
        return $this->belongsTo(Venue::class);
    }
}

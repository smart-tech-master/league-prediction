<?php

namespace App\Models\CustomFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\Prediction;
use App\Models\User;
use App\Scopes\SequentialLeagueScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
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

    public function competition(){
        return $this->belongsTo(Competition::class);
    }

    public function round(){
        return $this->belongsTo(Round::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function predictions(){
        return $this->belongsToMany(Prediction::class);
    }
}

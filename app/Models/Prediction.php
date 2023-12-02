<?php

namespace App\Models;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Scopes\SequentialLeagueScope;
use App\Services\PredictionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prediction extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';

    public function league(){
        return $this->setConnection('api-football')->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->setConnection('api-football')->belongsTo(Season::class);
    }

    public function round(){
        return $this->setConnection('api-football')->belongsTo(Round::class);
    }

    public function fixture(){
        return $this->setConnection('api-football')->belongsTo(Fixture::class);
    }

    public function user(){
        return $this->setConnection('mysql')->belongsTo(User::class);
    }

    public function points(){
        return PredictionService::points($this);
    }
}

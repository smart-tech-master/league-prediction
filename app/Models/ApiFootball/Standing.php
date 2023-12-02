<?php

namespace App\Models\ApiFootball;

use App\Scopes\SequentialLeagueScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $connection = 'api-football';

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

use App\Models\ApiFootball\League;

class LeagueUser extends Pivot
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    
    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }
}

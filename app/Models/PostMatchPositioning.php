<?php

namespace App\Models;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Scopes\SequentialLeagueScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostMatchPositioning extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }
}

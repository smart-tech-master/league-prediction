<?php

namespace App\Models\ApiFootball;

use App\Scopes\SequentialLeagueScope;
use App\Scopes\SerialScope;
use App\Services\ApiFootball\RoundService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $connection = 'api-football';
    public $timestamps = false;
    protected $appends = ['started_at', 'ended_at', 'points'];

    protected $casts = [
        'keywords' => 'object',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new SerialScope);
    }

    public function getPointsAttribute(){
        return $this->points();
    }

    public function points(){

        if(! auth('sanctum')->check()){
            return 0;
        }

        return (new RoundService())->points(auth('sanctum')->user(), $this);
    }

    public function getStartedAtAttribute(){
        return $this->fixtures()->select('timestamp')->orderBy('timestamp')->first()->timestamp ?? null;
    }

    public function getEndedAtAttribute(){
        return $this->fixtures()->select('timestamp')->orderByDesc('timestamp')->first()->timestamp ?? null;
    }

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function fixtures(){
        return $this->hasMany(Fixture::class);
    }

    public function competitionToRounds(){
        return $this->hasMany(\App\Models\CustomFootball\Round::class, 'to');
    }

    public function currentRound($query) {
        $query->where('current', true);
    } 
}

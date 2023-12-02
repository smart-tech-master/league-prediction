<?php

namespace App\Models\CustomFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\User;
use App\Scopes\SequentialLeagueScope;
use App\Services\CustomFootball\CompetitionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class Competition extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'custom-football';

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::created(function($model){
            $model->code = (new CompetitionService())->code($model);
            $model->save();
        });
    }

    public function subscribable(){
        return (new CompetitionService())->subscribable($this, request()->user());
    }

    // public function getCategoryAttribute(){
    //     return (new CompetitionService())->category($this);
    // }

    public function league(){
        return $this->belongsTo(League::class)->withoutGlobalScope(SequentialLeagueScope::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function round(){
        return $this->belongsTo(Round::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function competitors(){
        return $this->belongsToMany(User::class)->withPivot('created_at');
    }

    public function chats(){
        return $this->hasMany(Chat::class);
    }

    public function getCupStatusAttribute(){
        return CompetitionService::getCupStatus($this);
    }

    public function rounds(){
        return $this->hasMany(\App\Models\CustomFootball\Round::class);
    }
}
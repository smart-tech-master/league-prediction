<?php

namespace App\Models;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\CustomFootball\Competition;
use App\Services\RankService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'dob',
        'profile_picture',
        'bio',
        'device_token',
        'device_platform',
        'provider',
        'provider_id',
        'locale_id',
        'receive_notifications',
        'role',
        'country_id',
        'email_verified_at',
        'pin_code'
    ];

    protected $connection = 'mysql';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
    ];

    public function __construct($attributes = [])
    {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->getTable();
        parent::__construct($attributes);
    }

    public function getAvatarAttribute()
    {
        return (new UserService())->avatar($this);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, $this->getConnection()->getDatabaseName() . '.competition_user')->withTimestamps();
    }

    public function createdCompetitions()
    {
        return $this->hasMany(Competition::class);
    }

    public function leagues()
    {
        return $this->belongsToMany(League::class, $this->getConnection()->getDatabaseName() . '.league_user')->withPivot(['season_id'])->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    public function locale()
    {
        return $this->belongsTo(Locale::class)->withDefault();
    }

    public function scopePublicUser($query)
    {
        $query->whereRole('public-user');
    }

    public function likings()
    {
        return $this->morphMany(Liking::class, 'likeable');
    }

    public function rank()
    {
        return RankService::rank($this, null, Season::first()->id);
    }

    public function scopeAgedBetween($query, $start, $end)
    {
        if ($end == '+') {
            $end = 0;
        }

        return $query->whereBetween(DB::raw('YEAR(dob)'), [Carbon::now()->subYears($end)->format('Y'), Carbon::now()->subYears($start)->format('Y')]);
    }

    public function postMatchPositionings()
    {
        return $this->hasMany(PostMatchPositioning::class);
    }

    public function scopeIsNotBlocked($query)
    {
        $query->whereNull('blocked_at');
    }

    public function scopeIsVerified($query)
    {
        $query->where('pin_code', "verified");
    }

    public function remove($id) 
    {
        DB::connection($this->connection)->statement('SET FOREIGN_KEY_CHECKS=0');
        $this->where('id', $id)->forceDelete();
        DB::connection($this->connection)->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

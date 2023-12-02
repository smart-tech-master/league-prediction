<?php

namespace App\Models\CustomFootball;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'custom-football';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Chat::class);
    }

    public function competition(){
        return $this->belongsTo(Competition::class);
    }
}

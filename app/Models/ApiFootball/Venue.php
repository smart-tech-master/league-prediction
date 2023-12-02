<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $connection = 'api-football';
    public $timestamps = false;
}

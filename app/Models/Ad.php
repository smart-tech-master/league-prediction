<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function getBannerStatusAttribute(){
        if($this->type == 'banner'){
            if(Carbon::now()->between($this->started_at, $this->ended_at)){
                return 'Active';
            }else{
                return 'Inactive';
            }
        }
        return null;
    }
}

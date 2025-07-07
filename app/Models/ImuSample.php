<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImuSample extends Model
{
    protected $table = "imu_samples";

    protected $fillable = ['series_id', 'path'];

    public function series(){
        return $this->belongsTo(Serie::class,'series_id','id');
    }

}

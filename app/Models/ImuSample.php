<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImuSample extends Model
{
    protected $table = "imu_samples";

    protected $fillable = ['series_id', 'timestamp', 'gyr_x', 'gyr_y', 'gyr_z', 'acc_x', 'acc_y', 'acc_z'];

    public function series(){
        return $this->belongsTo(Serie::class,'series_id','id');
    }

}

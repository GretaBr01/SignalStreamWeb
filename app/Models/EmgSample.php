<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmgSample extends Model
{

    protected $table = "emg_samples";
    
    protected $fillable = ['series_id', 'path'];

    public function series(){
        return $this->belongsTo(Serie::class,'series_id','id');
    }
}

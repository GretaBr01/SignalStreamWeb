<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = "series";

    protected $fillable = ['label', 'started_at', 'ended_at', 'note', 'user_id'];

    public function emgSamples(){
        return $this->hasMany(EmgSample::class,'series_id','id');
    }

    public function imuSamples(){
        return $this->hasMany(ImuSample::class,'series_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }




}

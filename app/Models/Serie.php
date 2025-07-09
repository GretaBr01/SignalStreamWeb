<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = "series";

    protected $fillable = ['note', 'user_id', 'category_id'];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function category() {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function emgSamples(){
        return $this->hasMany(EmgSample::class, 'series_id');
    }

    public function imuSamples(){
        return $this->hasMany(ImuSample::class, 'series_id');
    }
}

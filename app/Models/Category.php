<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'image'];

    public function serie()
    {
        return $this->hasMany(Serie::class, 'category_id','id');
    }

    public static function getLabelFor(string $name): string
    {
        return __('categories.' . $name) ?? ucfirst($name);
    }

    // per poter scrivere $category->display_name ovunque nel codice
    public function getDisplayNameAttribute(): string
    {
        return self::getLabelFor($this->name);
    }

}

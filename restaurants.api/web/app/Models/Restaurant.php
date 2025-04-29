<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'menu_path',
        'latitude',
        'longitude',
        'telephone',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'restaurant_tags');
    }
}

<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriesFactory> */
    use HasFactory ,HasSlug;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];

    public function plants()
    {
        return $this->hasMany(Plants::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create() 
            ->generateSlugsFrom('name') // Génère le slug à partir du nom
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50); // Limite la longueur du slug à 50 caractères
    }

}

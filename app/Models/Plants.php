<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plants extends Model
{
    protected $table = 'plants';
    /** @use HasFactory<\Database\Factories\PlantsFactory> */
    use HasFactory ,HasSlug;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'stock',
        'status',
    ];

    public function images()
    {
        return $this->hasMany(PlantsImg::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create() 
            ->generateSlugsFrom('name') // Génère le slug à partir du nom
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50); // Limite la longueur du slug à 50 caractères
    }
}

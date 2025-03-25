<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plants extends Model
{
    /** @use HasFactory<\Database\Factories\PlantsFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'category_id',
        'price',
        'stock',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
            ->doNotGenerateSlugsOnUpdate();
    }
}

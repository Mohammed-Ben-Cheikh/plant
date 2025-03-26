<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantsImg extends Model
{
    protected $table = 'plants_imgs';
    protected $fillable = [
        'plants_id',
        'img_url',
        'is_primary',
    ];

    public function plant()
    {
        return $this->belongsTo(Plants::class);
    }
}

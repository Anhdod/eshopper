<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'original_price',
        'image',
        'color',     
        'sizes',     
        'stock',
    ];

 
    protected $casts = [
        'color' => 'array',  
        'sizes'  => 'array',
    ];

    protected $appends = [
        'images',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getImagesAttribute(): array
    {
        $images = $this->galleryImages
            ? $this->galleryImages->pluck('path')->filter()->values()->all()
            : [];

        if ($this->image && ! in_array($this->image, $images, true)) {
            array_unshift($images, $this->image);
        }

        return $images;
    }
}

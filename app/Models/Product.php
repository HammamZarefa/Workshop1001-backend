<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\IsActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'currency',
        'stock',
        'is_active',
        'is_featured',
        'is_special',
        'colors',
    ];

    protected $casts = [
        'colors' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_special'=>'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

       protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
    }
    public function registerMediaCollections(): void
{
    $this
        ->addMediaCollection('featured')
        ->singleFile();

    $this
        ->addMediaCollection('gallery');
}
    protected function price(): Attribute
    {
        return Attribute::make(

            get: fn ($value) => $value / 100,
            set: fn ($value) => (int) round($value * 100)
        );
    }
        //local scope
        public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }
    //rating
    public function ratings()
    {
    return $this->hasMany(Rating::class);
    }
    // rating calculation
    public function averageRating()
    {
    return $this->ratings()->avg('rating');
    }




}

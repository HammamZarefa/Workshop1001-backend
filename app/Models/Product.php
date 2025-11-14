<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\IsActiveScope;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
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
        'colors',
    ];

    protected $casts = [
        'colors' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
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

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Scopes\IsActiveScope;

class Banner extends Model implements HasMedia
{
     use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'link',
        'is_active',
        'sort_order',
    ];

    /**

     */
    public function registerMediaCollections(): void
    {

        $this->addMediaCollection('banners')
            ->singleFile();
    }

    /**
     */
   /* public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(150)
            ->sharpen(10);
    }*/

    /**

     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('banners') ?: null;
    }
        protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
    }
}

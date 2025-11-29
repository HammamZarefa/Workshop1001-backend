<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Scopes\IsActiveScope;
class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'is_active'];
    protected $appends = ['icon'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('categories')->singleFile();
    }

    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl('icon') ?: '';
    }

       protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
    }
}


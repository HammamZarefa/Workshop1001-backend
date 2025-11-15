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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
    }

       protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
    }
}


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

    protected $fillable = ['title', 'is_active','parent_id','display_order'];
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


public function parent()
{
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Category::class, 'parent_id')->orderBy('display_order');
}

}


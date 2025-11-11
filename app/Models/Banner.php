<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
      use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link',
        'is_active',
        'sort_order',
    ];
}

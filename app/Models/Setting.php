<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     protected $fillable = ['key', 'type', 'value'];

    protected $casts = [
        'value' => 'string',
    ];

    public function getParsedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($this->value) ? $this->value + 0 : 0,
            'json'    => json_decode($this->value, true),
            default   => $this->value,
        };
    }

    protected static function booted()
{
    static::saved(fn () => cache()->forget('settings'));
    static::deleted(fn () => cache()->forget('settings'));
}

}

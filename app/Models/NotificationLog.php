<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'channels',
        'sent_to',
    ];

    protected $casts = [
        'channels' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

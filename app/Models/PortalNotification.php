<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PortalNotification extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'portal_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
        'type',
        'link',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}

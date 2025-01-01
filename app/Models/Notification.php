<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
    ];

    // Relasi ke tabel user_notifications
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class, 'notification_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan (jika tidak mengikuti konvensi Laravel)
    protected $table = 'user_notifications';

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
        'read_at',
        'is_removed',
    ];

    // Relasi ke tabel notifications
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    // Relasi ke tabel users (asumsi ada model User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

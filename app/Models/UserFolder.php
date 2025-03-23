<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFolder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'folder_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savedSessions()
    {
        return $this->hasMany(UserLibrary::class, 'folder_id');
    }
}

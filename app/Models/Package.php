<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['*'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'packages_questions_pivot')
                    ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'package_user', 'package_id', 'user_id')
                    ->withTimestamps();
    }
}

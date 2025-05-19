<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function sessions()
    {
        return $this->belongsToMany(\App\Models\QuizSession::class, 'classroom_session');
    }
}

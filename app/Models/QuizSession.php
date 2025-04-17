<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class QuizSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'timer',
        'apply_all_timer',
        'access_code',
        'session_id',
        'is_public',
    ];

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function questions()
    {
        return $this->belongsToMany(QuizQuestion::class, 'quiz_sessions_quiz_questions', 'quiz_session_id', 'quiz_question_id')
                    ->withTimestamps();
    }

    public function libraries()
    {
        return $this->hasMany(UserLibrary::class, 'quiz_session_id', 'id')
            ->where('user_id', Auth::id());
    }

    public function attempts() {
        return $this->hasMany(QuizAttempt::class, 'session_id');
    }

}

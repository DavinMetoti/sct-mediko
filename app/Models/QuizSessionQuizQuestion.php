<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSessionQuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_sessions_quiz_questions';

    protected $fillable = [
        'quiz_session_id',
        'quiz_question_id',
    ];

    public $timestamps = true;

    public function quizQuestions()
    {
        return $this->belongsToMany(QuizQuestion::class, 'quiz_sessions_quiz_questions')
                    ->withTimestamps();
    }

    public function quizSessions()
    {
        return $this->belongsToMany(QuizSession::class, 'quiz_sessions_quiz_questions')
                    ->withTimestamps();
    }
}

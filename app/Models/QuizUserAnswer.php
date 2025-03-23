<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUserAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_attempts_id', 'quiz_question_id', 'quiz_answer_id', 'score'];

    /**
     * Relasi ke model QuizAttempt
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempts_id');
    }

    /**
     * Relasi ke model QuizQuestion
     */
    public function quizQuestion()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    /**
     * Relasi ke model QuizAnswer
     */
    public function quizAnswer()
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }
}

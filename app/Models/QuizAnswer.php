<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'answer',
        'value',
        'score'
    ];

    /**
     * Relationship: An answer belongs to a quiz question.
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}

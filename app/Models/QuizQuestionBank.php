<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestionBank extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: A question bank has many quiz questions.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_question_bank_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_bank_id',
        'medical_field_id',
        'column_title_id',
        'clinical_case',
        'initial_hypothesis',
        'new_information',
        'timer',
        'created_by',
        'explanation'
    ];

    /**
     * Relationship: A quiz question has many answers.
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Relationship: A quiz question belongs to a user (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function medicalField()
    {
        return $this->belongsTo(MedicalField::class);
    }

    public function quizQuestionBank()
    {
        return $this->belongsTo(QuizQuestionBank::class);
    }

    public function columnTitle()
    {
        return $this->belongsTo(ColumnTitle::class);
    }
}

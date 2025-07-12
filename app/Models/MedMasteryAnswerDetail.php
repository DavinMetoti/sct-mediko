<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedMasteryAnswerDetail extends Model
{
    protected $fillable = [
        'med_mastery_question_id',
        'med_mastery_answer_id',
        'answer_text',
        'is_correct',
        'self_assessment',
        'score',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function answer()
    {
        return $this->belongsTo(MedMasteryAnswer::class, 'med_mastery_answer_id');
    }

    public function question()
    {
        return $this->belongsTo(MedMasteryQuestion::class, 'med_mastery_question_id');
    }
}

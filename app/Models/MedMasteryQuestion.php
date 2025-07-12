<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedMasteryQuestion extends Model
{
    protected $table = 'med_mastery_questions';

    protected $fillable = [
        'medmastery_category_id',
        'question_text',
        'explanation',
        'explanation_pdf_path',
        'creator_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(MedmasteryCategory::class, 'medmastery_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    public function getExplanationPdfUrlAttribute()
    {
        return $this->explanation_pdf_path ? url('storage/' . $this->explanation_pdf_path) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('medmastery_category_id', $categoryId);
    }

    public function answerDetails()
    {
        return $this->hasMany(MedMasteryAnswerDetail::class, 'med_mastery_question_id');
    }
}

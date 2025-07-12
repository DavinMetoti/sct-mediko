<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedMasteryAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'med_mastery_category_id',
        'total_questions',
        'answer',
        'score',
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(MedmasteryCategory::class, 'med_mastery_category_id');
    }

    public function answerDetails()
    {
        return $this->hasMany(MedMasteryAnswerDetail::class, 'med_mastery_answer_id');
    }
}

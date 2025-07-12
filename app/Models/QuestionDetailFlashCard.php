<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDetailFlashCard extends Model
{
    protected $fillable = [
        'question_detail_id',
        'path',
        'panelist',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function questionDetail()
    {
        return $this->belongsTo(QuestionDetail::class, 'question_detail_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;

    protected $fillable = ['bank_name'];


    public function questionDetails()
    {
        return $this->hasMany(QuestionDetail::class, 'question_bank_id');
    }
}

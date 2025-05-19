<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuizAttempt extends Model {
    use HasFactory;

    protected $fillable = ['attempt_token', 'session_id', 'user_id','classroom_id', 'name', 'score', 'completed_at'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($attempt) {
            $attempt->attempt_token = Str::uuid()->toString(); // Ubah UUID ke string
        });
    }

    public function session() {
        return $this->belongsTo(QuizSession::class, 'session_id');
    }

    public function classroom() {
        return $this->belongsTo(\App\Models\Classroom::class, 'classroom_id');
    }

    public function answers() {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    public function userAnswer() {
        return $this->hasMany(QuizUserAnswer::class,'quiz_attempts_id');
    }
}

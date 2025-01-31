<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'question_id',
        'sisa_waktu',
        'score',
        'status',
        'completed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sisa_waktu' => 'integer',
        'score' => 'float',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationships
     */

    // TaskHistory belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // TaskHistory belongs to a Question
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function TaskHistory()
    {
        return $this->hasMany(TaskHistoryDetail::class);
    }
}

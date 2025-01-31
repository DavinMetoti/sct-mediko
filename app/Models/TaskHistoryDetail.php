<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistoryDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_history_id',
        'question_detail_id',
        'value',
        'status',
    ];

    /**
     * Get the task history that owns the detail.
     */
    public function taskHistory()
    {
        return $this->belongsTo(TaskHistory::class);
    }

    /**
     * Get the question detail associated with the task history detail.
     */
    public function questionDetail()
    {
        return $this->belongsTo(QuestionDetail::class);
    }
}

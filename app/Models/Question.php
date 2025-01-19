<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question',
        'description',
        'start_time',
        'end_time',
        'status',
        'created_by',
        'is_public',
        'time',
        'thumbnail',
    ];

    protected $dates = [
        'start_time',
        'end_time',
        'deleted_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'packages_questions_pivot');
    }
}

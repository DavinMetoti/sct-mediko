<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionDetailType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'minus_two',
        'minus_one',
        'zero',
        'one',
        'two',
    ];

    public $timestamps = true;

}
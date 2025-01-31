<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColumnTitle extends Model
{
    protected $table = 'column_titles';

    protected $fillable = [
        'name',
        'column_1',
        'column_2',
        'column_3',
    ];

    public $timestamps = true;
}

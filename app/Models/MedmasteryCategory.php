<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedmasteryCategory extends Model
{
    protected $table = 'medmastery_category';

    protected $fillable = [
        'medmastery_segmentation_id',
        'name',
        'description',
        'icon',
        'created_by',
        'access',
    ];

    public function segmentation()
    {
        return $this->belongsTo(MedmasterySegmentation::class, 'medmastery_segmentation_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(MedMasteryQuestion::class, 'medmastery_category_id');
    }

    public function activeQuestions()
    {
        return $this->hasMany(MedMasteryQuestion::class, 'medmastery_category_id')->where('is_active', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionDetail extends Model
{
    use HasFactory;


    protected $table = 'question_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_question',
        'id_medical_field',
        'clinical_case',
        'initial_hypothesis',
        'new_information',
        'discussion_image',
        'panelist_answers_distribution',
    ];

    public $timestamps = true;

    /**
     * Relasi ke model Question (many-to-one)
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question', 'id');
    }

    /**
     * Relasi ke model MedicalField (many-to-one)
     */
    public function medicalField()
    {
        return $this->belongsTo(MedicalField::class, 'id_medical_field', 'id');
    }
}

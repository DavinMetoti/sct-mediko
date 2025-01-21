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
        'clinical_case',
        'id_medical_field',
        'id_question_type',
        'id_sub_topic',
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

    /**
     * Relasi ke model MedicalField (many-to-one)
     */
    public function subTopic()
    {
        return $this->belongsTo(SubTopic::class, 'id_sub_topic', 'id');
    }

    /**
     * Relasi ke model MedicalField (many-to-one)
     */
    public function questionType()
    {
        return $this->belongsTo(QuestionDetailType::class, 'id_question_type', 'id');
    }
}

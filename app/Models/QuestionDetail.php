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
        'clinical_case',
        'id_medical_field',
        'id_question_type',
        'id_sub_topic',
        'question_bank_id',
        'initial_hypothesis',
        'new_information',
        'discussion_image',
        'panelist_answers_distribution',
        'column_title_id',
        'rationale',
    ];

    public $timestamps = true;

    /**
     * Relasi ke model Question (many-to-one)
     */
    public function question()
    {
        return $this->belongsToMany(Question::class, 'question_question_detail_pivot', 'question_detail_id', 'question_id');
    }

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
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

    public function columnTitle()
    {
        return $this->belongsTo(ColumnTitle::class);
    }

    public function historyAnswer()
    {
        return $this->belongsTo(QuestionDetail::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalField extends Model
{
    use HasFactory;


    protected $table = 'medical_fields';


    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
    ];

    public $timestamps = true;


    public function questionDetails()
    {
        return $this->hasMany(QuestionDetail::class, 'id_medical_field', 'id');
    }
}

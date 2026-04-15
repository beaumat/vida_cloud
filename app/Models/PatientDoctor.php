<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDoctor extends Model
{
    use HasFactory;
    protected $table = 'patient_doctor';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PATIENT_ID',
        'DOCTOR_ID'
    ];
}

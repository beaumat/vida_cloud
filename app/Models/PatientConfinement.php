<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientConfinement extends Model
{
    use HasFactory;

    protected $table = 'patient_confinement';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DATE_START',
        'DATE_END',
        'DESCRIPTION',
        'PATIENT_ID'
    ];
}

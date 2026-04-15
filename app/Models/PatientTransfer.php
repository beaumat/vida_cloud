<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTransfer extends Model
{
    use HasFactory;
    protected $table = 'patient_transfer';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PATIENT_ID',
        'DATE_TRANSFER',
        'NOTES'
    ];
}

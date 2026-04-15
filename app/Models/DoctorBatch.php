<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorBatch extends Model
{
    use HasFactory;
    protected $table = 'doctor_batch';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'CODE',
        'DOCTOR_ID',
        'LOCATION_ID'
    ];
}

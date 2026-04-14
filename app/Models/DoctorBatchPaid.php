<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorBatchPaid extends Model
{
    use HasFactory;
    protected $table = 'doctor_batch_paid';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'PAYMENT_PERIOD_ID',
        'CHECK_ID',
        'DOCTOR_BATCH_ID'
    ];
}

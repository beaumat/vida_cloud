<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cf4DoctorOrder extends Model
{
    use HasFactory;

    protected $table = 'cf4_doctor_order';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'DESCRIPTION'
    ];
}

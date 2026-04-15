<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorOrderDefault extends Model
{
    use HasFactory;
    protected $table = 'doctor_order_default';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'LINE_NO',
        'DESCRIPTION',
        'INACTIVE',
        'MODIFY'
    ];
}

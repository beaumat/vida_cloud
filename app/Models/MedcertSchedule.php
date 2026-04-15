<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedcertSchedule extends Model
{
    use HasFactory;
    protected $table = 'medcert_sched';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DESCRIPTION',
        'SHORT_DESCRIPTION',
    ];
}

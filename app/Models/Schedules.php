<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'SHIFT_ID',
        'CONTACT_ID',
        'SCHED_DATE',
        'SCHED_STATUS',
        'STATUS_LOG',
        'LOCATION_ID',
        'HEMO_MACHINE_ID',
        'CREATED_AT',
        'UPDATED_AT'
    ];
}

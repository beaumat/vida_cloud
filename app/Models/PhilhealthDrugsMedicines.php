<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilhealthDrugsMedicines extends Model
{
    use HasFactory;
    protected $table = 'philhealth_drugs_medicines';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PHILHEALTH_ID',
        'RECORDED_ON',
        'GENERIC_NAME',
        'QUANTITY',
        'DOSSAGE',
        'ROUTE',
        'FREQUENCY',
        'TOTAL_COST',
        'CONT_GENERIC_NAME',
        'CONT_QUANTITY',
        'CONT_DOSSAGE',
        'CONT_ROUTE',
        'CONT_FREQUENCY',
        'CONT_TOTAL_COST'
    ];
}

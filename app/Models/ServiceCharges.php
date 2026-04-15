<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCharges extends Model
{
    use HasFactory;
    protected $table = 'service_charges';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'PATIENT_ID',
        'LOCATION_ID',
        'AMOUNT',
        'BALANCE_DUE',
        'ACCOUNTS_RECEIVABLE_ID',
        'NOTES',
        'OUTPUT_TAX_ID',
        'OUTPUT_TAX_RATE',
        'OUTPUT_TAX_AMOUNT',
        'OUTPUT_TAX_VAT_METHOD',
        'OUTPUT_TAX_ACCOUNT_ID',
        'TAXABLE_AMOUNT',
        'NONTAXABLE_AMOUNT',
        'STATUS',
        'STATUS_DATE',
        'WALK_IN',
        'USE_PHIC'
    ];

   


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{   
    use HasFactory;

    protected $table = 'check';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'TYPE',
        'BANK_ACCOUNT_ID',
        'PAY_TO_ID',
        'LOCATION_ID',
        'AMOUNT',
        'NOTES',
        'PRINTED',
        'INPUT_TAX_ID',
        'INPUT_TAX_RATE',
        'INPUT_TAX_AMOUNT',
        'INPUT_TAX_VAT_METHOD',
        'INPUT_TAX_ACCOUNT_ID',
        'STATUS',
        'STATUS_DATE',
        'ACCOUNTS_PAYABLE_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5',
        'PF_PERIOD_ID'
    ];

    


}

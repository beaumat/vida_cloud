<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditMemo extends Model
{
    use HasFactory;
    protected $table = 'credit_memo';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'CUSTOMER_ID',
        'LOCATION_ID',
        'CLASS_ID',
        'SALES_REP_ID',
        'AMOUNT',
        'AMOUNT_APPLIED',
        'NOTES',
        'ACCOUNTS_RECEIVABLE_ID',
        'OUTPUT_TAX_ID',
        'OUTPUT_TAX_RATE',
        'OUTPUT_TAX_AMOUNT',
        'OUTPUT_TAX_VAT_METHOD',
        'OUTPUT_TAX_ACCOUNT_ID',
        'TAXABLE_AMOUNT',
        'NONTAXABLE_AMOUNT',
        'STATUS',
        'STATUS_DATE',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

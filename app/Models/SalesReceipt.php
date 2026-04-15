<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReceipt extends Model
{
    use HasFactory;


    protected $table = 'sales_receipt';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'POS_TIMESTAMP',
        'CUSTOMER_ID',
        'LOCATION_ID',
        'CLASS_ID',
        'SALES_REP_ID',
        'AMOUNT',
        'PAYMENT_AMOUNT',
        'PAYMENT_METHOD_ID',
        'PAYMENT_REF_NO',
        'CARD_NO',
        'CASHIER_ID',
        'CASH_COUNT_ID',
        'NOTES',
        'UNDEPOSITED_FUNDS_ACCOUNT_ID',
        'OUTPUT_TAX_ID',
        'OUTPUT_TAX_RATE',
        'OUTPUT_TAX_AMOUNT',
        'OUTPUT_TAX_VAT_METHOD',
        'OUTPUT_TAX_ACCOUNT_ID',
        'TAXABLE_AMOUNT',
        'NONTAXABLE_AMOUNT',
        'STATUS',
        'POS_POSTED',
        'POS_LOG_ID',
        'POS_MACHINE_ID',
        'SO_MACHINE_ID',
        'STATUS_DATE',
        'DEPOSITED',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5',
        'OR_REFERENCE_NO',
        'SO_REFERENCE_NO'
    ];
}

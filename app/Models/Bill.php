<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bill';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'VENDOR_ID',
        'LOCATION_ID',
        'PAYMENT_TERMS_ID',
        'DUE_DATE',
        'DISCOUNT_DATE',
        'DISCOUNT_PCT',
        'AMOUNT',
        'BALANCE_DUE',
        'NOTES',
        'ACCOUNTS_PAYABLE_ID',
        'INPUT_TAX_ID',
        'INPUT_TAX_RATE',
        'INPUT_TAX_AMOUNT',
        'INPUT_TAX_VAT_METHOD',
        'INPUT_TAX_ACCOUNT_ID',
        'STATUS',
        'STATUS_DATE',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5',
        'DATE_CONFIRM',
        'FILE_NAME',
        'FILE_PATH',
        'IS_XERO'

    ];
}

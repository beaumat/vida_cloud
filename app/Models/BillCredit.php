<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCredit extends Model
{
    use HasFactory;
    protected $table = 'bill_credit';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'VENDOR_ID',
        'LOCATION_ID',
        'AMOUNT',
        'AMOUNT_APPLIED',
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
        'CUSTOM_FIELD5'
    ];
}

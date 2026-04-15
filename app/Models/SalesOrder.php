<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;
    protected $table = 'sales_order';
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
        'DATE_NEEDED',
        'PO_NUMBER',
        'SHIP_TO',
        'SHIP_VIA_ID',
        'PAYMENT_TERMS_ID',
        'AMOUNT',
        'NOTES',
        'OUTPUT_TAX_ID',
        'OUTPUT_TAX_RATE',
        'OUTPUT_TAX_AMOUNT',
        'OUTPUT_TAX_VAT_METHOD',
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

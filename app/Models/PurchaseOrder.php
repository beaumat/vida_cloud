<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'VENDOR_ID',
        'LOCATION_ID',
        'CLASS_ID',
        'DATE_EXPECTED',
        'SHIP_TO',
        'SHIP_VIA_ID',
        'PAYMENT_TERMS_ID',
        'AMOUNT',
        'NOTES',
        'STATUS',
        'INPUT_TAX_ID',
        'INPUT_TAX_RATE',
        'INPUT_TAX_AMOUNT',
        'INPUT_TAX_VAT_METHOD',
        'INPUT_TAX_ACCOUNT_ID',
        'TAXABLE_AMOUNT',
        'NONTAXABLE_AMOUNT'
    ];
}

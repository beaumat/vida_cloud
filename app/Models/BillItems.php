<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItems extends Model
{
    use HasFactory;
    protected $table = 'bill_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'BILL_ID',
        'LINE_NO',
        'ITEM_ID',
        'DESCRIPTION',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'RATE',
        'RATE_TYPE',
        'AMOUNT',
        'BATCH_ID',
        'ACCOUNT_ID',
        'PO_ITEM_ID',
        'TAXABLE',
        'TAXABLE_AMOUNT',
        'TAX_AMOUNT',
        'CLASS_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5',
        'DISCOUNT_TYPE',
        'DISCOUNT_RATE',
        'ORG_AMOUNT',
        'STATUS'
    ];
}

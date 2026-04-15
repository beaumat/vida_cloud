<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItems extends Model
{
    use HasFactory;
    protected $table = 'sales_order_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'SALES_ORDER_ID',
        'LINE_NO',
        'ITEM_ID',
        'DESCRIPTION',
        'BATCH_ID',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'RATE',
        'RATE_TYPE',
        'AMOUNT',
        'TAXABLE',
        'TAXABLE_AMOUNT',
        'TAX_AMOUNT',
        'ESTIMATE_LINE_ID',
        'INVOICED_QTY',
        'CLOSED',
        'GROUP_LINE_ID',
        'PRINT_IN_FORMS',
        'PRICE_LEVEL_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

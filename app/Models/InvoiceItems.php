<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    use HasFactory;
    protected $table = 'invoice_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'INVOICE_ID',
        'LINE_NO',
        'ITEM_ID',
        'DESCRIPTION',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'RATE',
        'RATE_TYPE',
        'AMOUNT',
        'TAXABLE',
        'TAXABLE_AMOUNT',
        'TAX_AMOUNT',
        'COGS_ACCOUNT_ID',
        'ASSET_ACCOUNT_ID',
        'INCOME_ACCOUNT_ID',
        'REF_LINE_ID',
        'BATCH_ID',
        'GROUP_LINE_ID',
        'PRINT_IN_FORMS',
        'DEPOSITED',
        'PRICE_LEVEL_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];    
}

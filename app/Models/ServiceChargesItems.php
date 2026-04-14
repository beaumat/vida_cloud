<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceChargesItems extends Model
{
    use HasFactory;

    protected $table = 'service_charges_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'SERVICE_CHARGES_ID',
        'LINE_NO',
        'ITEM_ID',
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
        'GROUP_LINE_ID',
        'PRICE_LEVEL_ID',
        'PRINT_IN_FORMS',
        'PAID_AMOUNT',
        'DATE_LOG',
        'IS_POSTED',
        'INVOICE_ID',
    ];
}

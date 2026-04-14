<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferItems extends Model
{
    use HasFactory;
    protected $table = 'stock_transfer_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'STOCK_TRANSFER_ID',
        'LINE_NO',
        'ITEM_ID',
        'DESCRIPTION',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'UNIT_COST',
        'UNIT_PRICE',
        'AMOUNT',
        'RETAIL_VALUE',
        'BATCH_ID',
        'ASSET_ACCOUNT_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

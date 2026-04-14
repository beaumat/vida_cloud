<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentItems extends Model
{
    use HasFactory;

    protected $table = 'inventory_adjustment_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'INVENTORY_ADJUSTMENT_ID',
        'LINE_NO',
        'ITEM_ID',
        'QUANTITY',
        'UNIT_COST',
        'DESCRIPTION',
        'QTY_DIFFERENCE',
        'VALUE_DIFFERENCE',
        'ASSET_ACCOUNT_ID',
        'ASSET_VALUE',
        'BATCH_ID',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

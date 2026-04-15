<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAssetItem extends Model
{
    use HasFactory;

    protected $table = 'fixed_asset_item';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'LOCATION_ID',
        'ACCUMULATED_ACCOUNT_ID',
        'DEPRECIATION_ACCOUNT_ID',
        'PO_NUMBER',
        'SERIAL_NO',
        'WARRANTIY_EXPIRED',
        'PERSONAL_PROPERTY_RETURN',
        'IS_NEW',
        'OTHER_DESCRIPTION',
        'YEAR_PURCHASE',
        'YEAR_MODEL',
        'QUANTITY',
        'AQ_COST',
        'USEFUL_LIFE',
        'INACTIVE',
        'PO_DATE'
    ];
}

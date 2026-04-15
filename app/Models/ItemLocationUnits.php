<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLocationUnits extends Model
{
    use HasFactory;

    protected $table = 'item_location_units';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'PRICE_LEVEL_ID',
        'CUSTOM_PRICE',
        'LOCATION_ID',
        'PURCHASES_UNIT_ID',
        'SALES_UNIT_ID',
        'SHIPPING_UNIT_ID'
    ];
}

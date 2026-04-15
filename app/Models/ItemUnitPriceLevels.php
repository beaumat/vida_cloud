<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnitPriceLevels extends Model
{
    use HasFactory;

    protected $table = 'item_unit_price_levels';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_UNIT_LINE_ID',
        'PRICE_LEVEL_ID',
        'CUSTOM_PRICE',
        'CUSTOM_COST'
    ];

}

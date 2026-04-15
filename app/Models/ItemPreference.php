<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPreference extends Model
{
    use HasFactory;

    protected $table = 'item_preference';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'LOCATION_ID',
        'ORDER_POINT',
        'ORDER_QTY',
        'ORDER_LEADTIME',
        'ONHAND_MAX_LIMIT',
        'STOCK_BIN_ID'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSoa extends Model
{
    use HasFactory;

    protected $table = 'soa_item';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'LINE',
        'TYPE',
        'ITEM_NAME',
        'UNIT_NAME',
        'RATE',
        'ACTUAL_BASE',
        'DOSAGE',
        'ROUTE',
        'FREQUENCY',
        'BRAND',
        'INACTIVE',
        'GROUP_ID',
        'SC_BASE',
        'SOA_BASE',
        'GENERIC_NAME',
        'FIX_QTY',
        'ITEM_CONTROL_A',
        'ITEM_CONTROL_B',
        'ITEM_HIDE'
    ];
}

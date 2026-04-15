<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInventory extends Model
{
    use HasFactory;
    protected $table = 'item_inventory';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PREVIOUS_ID',
        'SEQUENCE_NO',
        'ITEM_ID',
        'LOCATION_ID',
        'BATCH_ID',
        'SOURCE_REF_TYPE',
        'SOURCE_REF_ID',
        'SOURCE_REF_DATE',
        'QUANTITY',
        'COST',
        'ENDING_QUANTITY',
        'ENDING_UNIT_COST',
        'ENDING_COST'
    ];
}

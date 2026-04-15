<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnits extends Model
{
    use HasFactory;

    protected $table = 'item_units';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'UNIT_ID',
        'QUANTITY',
        'RATE',
        'BARCODE'
    ];
}

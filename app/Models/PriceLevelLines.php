<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceLevelLines extends Model
{
    use HasFactory;

    protected $table = 'price_level_lines';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PRICE_LEVEL_ID',
        'ITEM_ID',
        'CUSTOM_PRICE',
        'CUSTOM_COST'
    ];
}

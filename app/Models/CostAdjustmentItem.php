<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostAdjustmentItem extends Model
{
    use HasFactory;
    protected $table = 'cost_adjustment_item';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'COST_ADJUSTMENT_ID',
        'LINE_NO',
        'ITEM_ID',
        'COST',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepreciationItems extends Model
{
    use HasFactory;

    protected $table = 'depreciation_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DEPRECIATION_ID',
        'FIXED_ASSET_ITEM_ID',
        'AMOUNT',
        'ACCOUNT_ID'
    ];
}

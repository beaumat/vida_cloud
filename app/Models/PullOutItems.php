<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutItems extends Model
{
    use HasFactory;
    protected $table = 'pull_out_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PULL_OUT_ID',
        'LINE_NO',
        'ITEM_ID',
        'DESCRIPTION',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'RATE',
        'AMOUNT',
        'BATCH_ID',
        'ASSET_ACCOUNT_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

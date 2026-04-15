<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HemodialysisItems extends Model
{
    use HasFactory;

    protected $table = 'hemodialysis_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'LINE_NO',
        'ITEM_ID',
        'QUANTITY',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'IS_NEW',
        'IS_DEFAULT',
        'IS_POST',
        'SC_ITEM_ID',
        'IS_CASHIER',
        'SK_LINE_ID',
        'IS_JUSTIFY',
        'JUSTIFY_NOTES'
    ];
}

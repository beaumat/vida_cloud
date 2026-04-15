<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildAssembly extends Model
{
    use HasFactory;
    
    protected $table = 'build_assembly';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'LOCATION_ID',
        'ASSEMBLY_ITEM_ID',
        'QUANTITY',
        'AMOUNT',
        'BATCH_ID',
        'UNIT_ID',
        'UNIT_BASE_QUANTITY',
        'NOTES',
        'ASSET_ACCOUNT_ID',
        'STATUS',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

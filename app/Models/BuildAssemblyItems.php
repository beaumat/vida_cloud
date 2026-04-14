<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildAssemblyItems extends Model
{
    use HasFactory;


    protected $table = 'build_assembly_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'BUILD_ASSEMBLY_ID',
        'ITEM_ID',
        'QUANTITY',
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

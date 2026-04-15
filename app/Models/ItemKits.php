<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemKits extends Model
{
    use HasFactory;


    protected $table = 'item_kits';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'COMPONENT_ID',
        'LOCATION_ID',
        'QUANTITY',
    ];
}

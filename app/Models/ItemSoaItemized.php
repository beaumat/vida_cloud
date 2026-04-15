<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSoaItemized extends Model
{
    use HasFactory;
    protected $table = 'item_soa_itemized';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'SOA_ITEM_ID',
        'INACTIVE'
    ];

}

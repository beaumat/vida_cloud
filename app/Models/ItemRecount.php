<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRecount extends Model
{
    use HasFactory;
    protected $table = 'item_recount';

    public $timestamps = false;
    protected $fillable = [
        'ITEM_ID',
        'LOCATION_ID',
        'DATE_ON',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComponents extends Model
{
    use HasFactory;
    protected $table = 'item_components';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_ID',
        'COMPONENT_ID',
        'QUANTITY',
        'RATE'
    ];
}

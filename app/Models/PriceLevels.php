<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceLevels extends Model
{
    use HasFactory;
    protected $table = 'price_level';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION',
        'TYPE',
        'RATE',
        'ITEM_GROUP_ID',
        'INACTIVE'
    ];
}

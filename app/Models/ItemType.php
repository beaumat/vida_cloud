<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $table = 'item_type_map';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DESCRIPTION',
        'NEXT_ID',
        'DIGIT_COUNT',
        'FIRST_CHARACTER'
      
    ];
}

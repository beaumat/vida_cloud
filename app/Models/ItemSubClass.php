<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubClass extends Model
{
    use HasFactory;

    protected $table = 'item_sub_class';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION',
        'CLASS_ID',
        'IN_HEMO'
    ];
}

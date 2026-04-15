<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationReference extends Model
{
    use HasFactory;

    protected $table = 'location_reference';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'TABLE_NAME',
        'NEXT_CODE',
        'DIGIT_CODE',
        'SYMBOL_CODE'
    ];
}

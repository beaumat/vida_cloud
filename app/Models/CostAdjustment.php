<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostAdjustment extends Model
{
    use HasFactory;
    protected $table = 'cost_adjustment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'LOCATION_ID',
        'STATUS',
        'STATUS_DATE'
    ];
}

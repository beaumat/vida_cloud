<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilhealthItemAdjustment extends Model
{
    use HasFactory;

    protected $table = 'philhealth_item_adjustment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PATIENT_ID',
        'LOCATION_ID',
        'NO_OF_USED',
        'YEAR',
        'NOTES',
        'FILE_NAME',
        'FILE_PATH',
        'NO_OF_ITEM'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilHealthSoaCustom extends Model
{
    use HasFactory;
    protected $table = 'philhealth_soa_custom';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'DESCRIPTION',
        'DRUG_MED',
        'LAB_DIAG',
        'OPERATING_ROOM_FEE',
        'SUPPLIES',
        'ADMIN_OTHER_FEE',
        'DRUG_MED_PK',
        'LAB_DIAG_PK',
        'OPERATING_ROOM_FEE_PK',
        'SUPPLIES_PK',
        'ADMIN_OTHER_FEE_PK',
        'INACTIVE',
        'LINE_NO',
        'ACTUAL_FEE',
        'HIDE_FEE'


    ];
}

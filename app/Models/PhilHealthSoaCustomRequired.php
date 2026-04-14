<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilHealthSoaCustomRequired extends Model
{
    use HasFactory;
    protected $table = 'philhealth_soa_custom_required';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'SOA_CUSTOM_ID',
        'ITEM_ID'
    ];
}

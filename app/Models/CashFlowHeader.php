<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowHeader extends Model
{
    use HasFactory;

    protected $table = 'cash_flow_header';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'NAME',
        'LOCATION_ID',
        'LINE_NO',
        'INACTIVE',
        'RECORDED_ON'
    ];
}

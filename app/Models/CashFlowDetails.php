<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowDetails extends Model
{
    use HasFactory;
    protected $table = 'cash_flow_details';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'CF_HEADER_ID',
        'NAME',
        'LINE_NO',
        'RECORDED_ON',
        'INACTIVE',
        'IS_TOTAL'
    ];
}

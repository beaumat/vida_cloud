<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransferReverse extends Model
{
    use HasFactory;

    protected $table      = 'fund_transfer_reverse';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'NOTES',
        'FUND_TRANSFER_ID',
        'USERNAME',
        'LOCATION_ID',
    ];
}

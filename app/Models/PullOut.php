<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOut extends Model
{
    use HasFactory;

    protected $table = 'pull_out';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'LOCATION_ID',
        'AMOUNT',
        'NOTES',
        'PREPARED_BY_ID',
        'STATUS',
        'STATUS_DATE',
        'ACCOUNT_ID',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5'
    ];
}

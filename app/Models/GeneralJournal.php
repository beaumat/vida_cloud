<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;
    protected $table = 'general_journal';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'CODE',
        'LOCATION_ID',
        'ADJUSTING_ENTRY',
        'NOTES',
        'STATUS',
        'STATUS_DATE',
        'CONTACT_ID',
        'IS_XERO',
    ];
}

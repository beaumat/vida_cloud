<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalDetails extends Model
{
    use HasFactory;
    protected $table = 'general_journal_details';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'GENERAL_JOURNAL_ID',
        'LINE_NO',
        'ACCOUNT_ID',
        'ENTRY_TYPE',
        'DEBIT',
        'CREDIT',
        'AMOUNT',
        'NOTES',
        'CLASS_ID'
    ];
}

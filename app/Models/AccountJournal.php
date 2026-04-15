<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountJournal extends Model
{
    use HasFactory;
    protected $table      = 'account_journal';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'PREVIOUS_ID',
        'SEQUENCE_NO',
        'JOURNAL_NO',
        'ACCOUNT_ID',
        'LOCATION_ID',
        'SUBSIDIARY_ID',
        'SEQUENCE_GROUP',
        'OBJECT_TYPE',
        'OBJECT_ID',
        'OBJECT_DATE',
        'ENTRY_TYPE',
        'AMOUNT',
        'ENDING_BALANCE',
        'EXTENDED_OPTIONS',
    ];

}

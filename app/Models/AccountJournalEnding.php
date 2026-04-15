<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountJournalEnding extends Model
{
    use HasFactory;
    protected $table      = 'account_journal_ending';
    protected $primaryKey = 'AJ_ID';
    public $timestamps    = false;
    protected $fillable   = [
        'AJ_ID',
    ];
}

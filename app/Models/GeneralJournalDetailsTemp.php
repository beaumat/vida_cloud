<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalDetailsTemp extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'general_journal_details_temp';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ACCOUNT_ID',
        'NOTES',
    ];
}

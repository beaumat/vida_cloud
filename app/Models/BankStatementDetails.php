<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankStatementDetails extends Model
{
    use HasFactory;
    protected $table      = 'bank_statement_details';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'BANK_STATEMENT_ID',
        'DATE_TRANSACTION',
        'REFERENCE',
        'DESCRIPTION',
        'CHECK_NUMBER',
        'DEBIT',
        'CREDIT',
        'BALANCE',
        'OBJECT_TYPE',
        'OBJECT_ID',
        'RECON_LOG',
    ];
}

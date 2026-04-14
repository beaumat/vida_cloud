<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostingLog extends Model
{
    use HasFactory;

    // posting_log

    protected $table      = 'posting_log';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'DATE',
    ];
}

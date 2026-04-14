<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;

    protected $table      = 'file_type_map';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'DESCRIPTION',
    ];
}

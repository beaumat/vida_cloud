<?php
namespace App\Services;

use App\Models\FileType;

class FileTypeServices
{
    public function getFileTypes()
    {
        return FileType::all();
    }

}

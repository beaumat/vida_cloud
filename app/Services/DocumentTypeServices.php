<?php

namespace App\Services;

use App\Models\DocumentTypeMap;

class DocumentTypeServices
{

    public function getId(string $DESCRIPTION)
    {
        $data = DocumentTypeMap::where('DESCRIPTION', $DESCRIPTION)->first();
        if ($data) {
            return (int) $data->ID;
        }
        dd($DESCRIPTION . " not found.");
        return 0;
    }
}

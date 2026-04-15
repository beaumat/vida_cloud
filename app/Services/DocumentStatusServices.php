<?php

namespace App\Services;

use App\Models\DocumentStatus;

class DocumentStatusServices
{

    public function getDesc(int $ID): string
    {
        return DocumentStatus::where('ID', $ID)->first()->DESCRIPTION;
    }
    public function getID(string $DESCRIPTION): int
    {
        $data = DocumentStatus::where('DESCRIPTION', $DESCRIPTION)->first();
        if ($data) {
            return (int) $data->ID;
        }
        dd($DESCRIPTION . " not found.");
        return 0;
    }
}

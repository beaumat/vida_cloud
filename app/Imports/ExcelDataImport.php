<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;


class ExcelDataImport implements ToArray
{
    public $data = [];

    /**
    * @param Collection $collection
    */
    public function array(array $array)
    {
        // Store the data in a variable
        $this->data = $array;
    }
}

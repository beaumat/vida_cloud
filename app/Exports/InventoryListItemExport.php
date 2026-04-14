<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryListItemExport implements FromCollection, ShouldAutoSize
{
    protected  $dataList = [];

    public function __construct($dataList)
    {
        $this->dataList = $dataList;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $finalData = [];

        $headers = [
            'CATEGORY'          => 'CATEGORY',
            'SUB_CATEGORY'      => 'SUB-CATEGORY',
            'CODE'              => 'CODE',
            'DESCRIPTION'       => 'DESCRIPTION',
            'UNIT'              => 'UNIT',
            'ONHAND'            => 'ON-HAND'
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {
            $rowData = [
                'CATEGORY'          => $list->CLASS_NAME,
                'SUB_CATEGORY'      => $list->SUB_NAME,
                'CODE'              => $list->CODE,
                'DESCRIPTION'       => $list->DESCRIPTION,
                'UNIT'              => $list->SYMBOL,
                'ONHAND'            => $list->QTY_ON_HAND

            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

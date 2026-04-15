<?php

namespace App\Exports;

use App\Services\ItemServices;
use App\Services\LocationServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryReportExport implements FromCollection, ShouldAutoSize
{
    protected $dataList = [];
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
            'TYPE'          => 'TYPE',
            'DATE'          => 'DATE',
            'REFERENCE'     => 'REFERENCE',
            'NAME'          => 'NAME',
            'NOTES'         => 'NOTES',
            'QTY'           => 'QTY',
            'ENDING_QTY'    => 'ENDING_QTY'
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {
            $rowData = [
                'TYPE'          => $list->TYPE,
                'DATE'          => $list->SOURCE_REF_DATE,
                'REFERENCE'     => $list->TX_CODE,
                'NAME'          => $list->CONTACT_NAME,
                'NOTES'         => $list->TX_NOTES,
                'QTY'           => $list->QUANTITY ?? 0,
                'ENDING_QTY'    => number_format($list->ENDING_QUANTITY, 1)
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

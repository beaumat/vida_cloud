<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CashFlowExport implements FromCollection, ShouldAutoSize
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
            'ACCOUNT'    => 'ACCOUNT',
            'AMOUNT'     => 'AMOUNT'
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {

            $rowData = [
                'ACCOUNT'    => $list['name'],
                'AMOUNT'    => $list['amount'],
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

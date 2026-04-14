<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IncomeStatementExport implements FromCollection, ShouldAutoSize
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
            'TOTAL'      => 'TOTAL'
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {
            $rowData = [
                'ACCOUNT'  => $list['ACCOUNT_NAME'],
                'TOTAL'    => $list['TOTAL'],
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

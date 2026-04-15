<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrialBalanceExport implements FromCollection, ShouldAutoSize
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
        $TOTAL_DEBIT = 0;
        $TOTAL_CREDIT = 0;
        $headers = [
            'ACCOUNT_NAME'  => 'ACCOUNT TITLE',
            'DEBIT'         => 'DEBIT',
            'CREDIT'        => 'CREDIT'
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {


            $rowData = [
                'ACCOUNT_NAME'  => $list->ACCOUNT_TITLE,
                'DEBIT'         => $list->TX_DEBIT,
                'CREDIT'        => $list->TX_CREDIT
            ];

            $TOTAL_DEBIT = $TOTAL_DEBIT + $list->TX_DEBIT;
            $TOTAL_CREDIT = $TOTAL_CREDIT + $list->TX_CREDIT;
            $finalData[] = array_values($rowData);
        }


        $rowData = [
            'ACCOUNT_NAME'  => 'TOTAL',
            'DEBIT'         => $TOTAL_DEBIT,
            'CREDIT'        => $TOTAL_CREDIT
        ];
        $finalData[] = array_values($rowData);


        return collect($finalData);
    }
}

<?php

namespace App\Exports;

use App\Services\NumberServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IncomeStatementMonthlyExport implements FromCollection, ShouldAutoSize
{
    protected $dataList = [];
    private $numberServices;
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
            'ACCOUNT' => 'ACCOUNT',
            'JAN' => 'JAN',
            'FEB' => 'FEB',
            'MAR' => 'MAR',
            'APR' => 'APR',
            'MAY' => 'MAY',
            'JUN' => 'JUN',
            'JUL' => 'JUL',
            'AUG' => 'AUG',
            'SEP' => 'SEP',
            'OCT' => 'OCT',
            'NOV' => 'NOV',
            'DEC' => 'DEC',
            'TOTAL' => 'TOTAL',
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {
            $rowData = [
                'ACCOUNT' => $list['ACCOUNT_NAME'],
                'JAN' => NumberServices::Decimal2Only($list['JAN']) > 0 ? NumberServices::Decimal2Only($list['JAN']) : '',
                'FEB' => NumberServices::Decimal2Only($list['FEB']) > 0 ? NumberServices::Decimal2Only($list['FEB']) : '',
                'MAR' => NumberServices::Decimal2Only($list['MAR']) > 0 ? NumberServices::Decimal2Only($list['MAR']) : '',
                'APR' => NumberServices::Decimal2Only($list['MAR']) > 0 ? NumberServices::Decimal2Only($list['MAR']) : '',
                'MAY' => NumberServices::Decimal2Only($list['APR']) > 0 ? NumberServices::Decimal2Only($list['APR']) : '',
                'JUN' => NumberServices::Decimal2Only($list['MAY']) > 0 ? NumberServices::Decimal2Only($list['MAY']) : '',
                'JUL' => NumberServices::Decimal2Only($list['JUL']) > 0 ? NumberServices::Decimal2Only($list['JUL']) : '',
                'AUG' => NumberServices::Decimal2Only($list['AUG']) > 0 ? NumberServices::Decimal2Only($list['AUG']) : '',
                'SEP' => NumberServices::Decimal2Only($list['SEP']) > 0 ? NumberServices::Decimal2Only($list['SEP']) : '',
                'OCT' => NumberServices::Decimal2Only($list['OCT']) > 0 ? NumberServices::Decimal2Only($list['OCT']) : '',
                'NOV' => NumberServices::Decimal2Only($list['NOV']) > 0 ? NumberServices::Decimal2Only($list['NOV']) : '',
                'DEC' => NumberServices::Decimal2Only($list['DEC']) > 0 ? NumberServices::Decimal2Only($list['DEC']) : '',
                'TOTAL' => NumberServices::Decimal2Only($list['TOTAL']) > 0 ? NumberServices::Decimal2Only($list['TOTAL']) : '',
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

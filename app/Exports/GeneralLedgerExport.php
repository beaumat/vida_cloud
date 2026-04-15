<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GeneralLedgerExport implements FromCollection, ShouldAutoSize
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
            'TYPE'      => 'TYPE',
            'DATE'      => 'DATE',
            'REFERENCE' => 'REFERENCE #',
            'NAME'      => 'NAME',
            'LOCATION'  => 'LOCATION',
            'NOTES'     => 'NOTES',
            'DEBIT'     => 'DEBIT',
            'CREDIT'    => 'CREDIT',
            'BALANCE'   => 'BALANCE'
        ];

        $finalData[] = array_values($headers);


        $TEMP_ACCOUNT = '';
        $TEMP_DEBIT = 0;
        $TEMP_CREDIT = 0;
        $TOTAL_DEBIT = 0;
        $TOTAL_CREDIT = 0;
        $BALANCE = 0;

        foreach ($this->dataList as $list) {

            if ($TEMP_ACCOUNT == '') {
                $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                $rowData = [
                    'TYPE'          => $TEMP_ACCOUNT,
                    'DATE'          => '',
                    'REFERENCE'     => '',
                    'NAME'          => '',
                    'LOCATION'      => '',
                    'NOTES'         => '',
                    'DEBIT'         => '',
                    'CREDIT'        => '',
                    'BALANCE'       => ''
                ];

                $finalData[] = array_values($rowData);
            } else {
                if ($TEMP_ACCOUNT != $list->ACCOUNT_TITLE) {
                    // sub total
                    $rowData = [
                        'TYPE'          => '',
                        'DATE'          => '',
                        'REFERENCE'     => '',
                        'NAME'          => '',
                        'LOCATION'      => '',
                        'NOTES'         => '',
                        'DEBIT'         => $TEMP_DEBIT > 0 ? $TEMP_DEBIT  : 0,
                        'CREDIT'        => $TEMP_CREDIT > 0 ? $TEMP_CREDIT  : 0,
                        'BALANCE'       => ''
                    ];
                    $finalData[] = array_values($rowData);
                    $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                    $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                    $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                    $rowData = [
                        'TYPE'          => $TEMP_ACCOUNT,
                        'DATE'          => '',
                        'REFERENCE'     => '',
                        'NAME'          => '',
                        'LOCATION'      => '',
                        'NOTES'         => '',
                        'DEBIT'         => '',
                        'CREDIT'        => '',
                        'BALANCE'       => ''
                    ];

                    $finalData[] = array_values($rowData);
                } else {

                    $TEMP_DEBIT += (float) $list->DEBIT ?? 0;
                    $TEMP_CREDIT += (float) $list->CREDIT ?? 0;
                }
            }

            if ($list->DEBIT > 0) {
                $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT;
            }

            if ($list->CREDIT > 0) {
                $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT;
            }

            if ($list->DEBIT > 0) {
                $BALANCE = $BALANCE + $list->DEBIT ?? 0;
            } else {
                $BALANCE = $BALANCE - $list->CREDIT ?? 0;
            }


            $rowData = [
                'TYPE'          => $list->TYPE,
                'DATE'          => date('m/d/Y', strtotime($list->DATE)),
                'REFERENCE'     => $list->TX_CODE,
                'NAME'          => $list->TX_NAME,
                'LOCATION'      => $list->LOCATION,
                'NOTES'         => $list->TX_NOTES,
                'DEBIT'         => $list->DEBIT > 0 ? $list->DEBIT : 0,
                'CREDIT'        => $list->CREDIT > 0 ? $list->CREDIT : 0,
                'BALANCE'       => $BALANCE
            ];

            $finalData[] = array_values($rowData);
        }
        // sub total
        $rowData = [
            'TYPE'          => '',
            'DATE'          => '',
            'REFERENCE'     => '',
            'NAME'          => '',
            'LOCATION'      => '',
            'NOTES'         => '',
            'DEBIT'         =>  $TEMP_DEBIT > 0 ? $TEMP_DEBIT  : 0,
            'CREDIT'        =>  $TEMP_CREDIT > 0 ? $TEMP_CREDIT : 0,
            'BALANCE'       => ''
        ];
        $finalData[] = array_values($rowData);


        // total
        $rowData = [
            'TYPE'          => '',
            'DATE'          => '',
            'REFERENCE'     => '',
            'NAME'          => '',
            'LOCATION'      => '',
            'NOTES'         => '',
            'DEBIT'         =>  $TOTAL_DEBIT > 0 ? $TOTAL_DEBIT  : '0.00',
            'CREDIT'        =>  $TOTAL_CREDIT > 0 ? $TOTAL_CREDIT : '0.00',
            'BALANCE'       => ''
        ];
        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionDetailsExport implements FromCollection, ShouldAutoSize
{

    private $dataList = [];

    public function __construct($dataList)
    {
        $this->dataList = $dataList;
    }
    private function InsertData($DATE, $TYPE, $TX_NAME, $TX_CODE, $LOCATION, $DEBIT, $CREDIT, $BALANCE, $GROSS)
    {
        $dataArray = [
            'DATE' => $DATE,
            'TYPE' => $TYPE,
            'TX_NAME' => $TX_NAME,
            'TX_CODE' => $TX_CODE,
            'LOCATION' => $LOCATION,
            'DEBIT' => $DEBIT,
            'CREDIT' => $CREDIT,
            'BALANCE' => $BALANCE,
            'GROSS' => $GROSS,
        ];
        return array_values($dataArray);
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {

        $TOTAL_DEBIT = 0;
        $TOTAL_CREDIT = 0;
        $BALANCE = 0;
        $TEMP_ACCOUNT = '';
        $TEMP_DEBIT = 0;
        $TEMP_CREDIT = 0;




        $finalData[] = $this->InsertData('Date', 'Type', 'Name', 'Reference', 'Location', 'Debit', 'Credit', 'Running Bal.', 'Gross');


        foreach ($this->dataList as $list) {
            if ($TEMP_ACCOUNT == '') {
                $BALANCE = $list->BALANCE ?? 0;
                $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                $finalData[] = $this->InsertData($TEMP_ACCOUNT, '', '', '', '', '', '', '', '');

            } else {

                if ($TEMP_ACCOUNT != $list->ACCOUNT_TITLE) {
                    $finalData[] = $this->InsertData('', '', '', '', '', $TEMP_DEBIT, $TEMP_CREDIT, $BALANCE, '');
                    $BALANCE = $list->BALANCE ?? 0;

                    $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                    $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                    $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                    $finalData[] = $this->InsertData($TEMP_ACCOUNT, '', '', '', '', '', '', '', '');

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

            $finalData[] = $this->InsertData($list->JOURNAL_NO != 'F' ? date('m/d/Y', strtotime($list->DATE)) : '', $list->TYPE, $list->TX_NAME, $list->TX_CODE, $list->LOCATION, $list->DEBIT > 0 ? $list->DEBIT : 0, $list->CREDIT > 0 ? $list->CREDIT : 0, $BALANCE, $list->JOURNAL_NO != 'F' ? ($list->DEBIT > 0 ? $list->DEBIT : $list->CREDIT) : '');
        }

        $finalData[] = $this->InsertData($TEMP_ACCOUNT, '', '', '', '', $TEMP_DEBIT, $TEMP_CREDIT, $BALANCE > 0 ? $BALANCE : '', '');

        return collect($finalData);
    }
}

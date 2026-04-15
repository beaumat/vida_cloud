<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuaranteeLetterExport implements FromCollection, ShouldAutoSize
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
            'PATIENT' => 'PATIENT',
            'GL_TYPE' => 'GL_TYPE',
            'GL_DATE' => 'GL_DATE',
            'GL_NO' => 'GL_NO',
            'AMOUNT' => 'AMOUNT',
            'USAGE' => 'USAGE',
            'BALANCE' => 'BALANCE',
        ];
        $finalData[] = array_values($headers);
        $TEMP_NAME = "";
        $BALANCE = 0;

        foreach ($this->dataList as $list) {
            $PATIENT_NAME = "";
            if ($list->PATIENT_NAME <> $TEMP_NAME) {
                $PATIENT_NAME = $list->PATIENT_NAME ?? '';
            }

            $BALANCE += $list->BALANCE ?? 0;
            $rowData = [
                'PATIENT' => $PATIENT_NAME,
                'GL_TYPE' => $list->METHOD,
                'GL_DATE' => $list->TRANS_DATE,
                'GL_NO' => $list->TRANS_CODE,
                'AMOUNT' => $list->AMOUNT,
                'USAGE' => $list->AMOUNT_APPLIED,
                'BALANCE' => $list->BALANCE,
            ];
            $finalData[] = array_values($rowData);
            $TEMP_NAME = $list->PATIENT_NAME;
        }

        $rowData = [
            'PATIENT' => '',
            'GL_TYPE' => '',
            'GL_DATE' => '',
            'GL_NO' => '',
            'AMOUNT' => '',
            'USAGE' => '',
            'BALANCE' => $BALANCE,
        ];     
        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}

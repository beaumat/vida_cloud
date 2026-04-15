<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\OtherServices;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PhilhealthMonitoringExport implements FromCollection, ShouldAutoSize
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
            'NO'            => 'No.',
            'DATE'          => 'Date Trans.',
            'LHIO'          => 'Series LHIO',
            'NAME'          => 'Name of Patient',
            'CON'           => 'Confinement Period',
            'NOT'           => 'No. of Treatment',
            'FIRST_CASE'    => 'First Case Amount',
            'D_PAID'        => 'Date Paid',
            'OR'            => 'OR Number',
            'WTAX'          => 'Wtax',
            'PAID'          => 'Paid',
            'GROSS'         => 'Gross',
            'PF'            => 'Doctor PF',
            'NET'           => 'Net Amount',
            'STATUS'        => 'Status',
        ];


        $finalData[] = array_values($headers);
        $TOTAL_NOT = 0;
        $running = 0;
        $TOTAL_AMOUNT = 0;
        $TOTAL_WTAX = 0;
        $TOTAL_PAID = 0;
        $TOTAL_GROSS = 0;
        $TOTAL_PF = 0;
        $TOTAL_NET = 0;

        foreach ($this->dataList as $list) {
            $running++;
            $TOTAL_NOT  += $list->HEMO_TOTAL ?? 0;
            $TOTAL_AMOUNT += $list->P1_TOTAL ?? 0;
            $TOTAL_WTAX += $list->TAX_AMOUNT ?? 0;
            $TOTAL_PAID += $list->PAID_AMOUNT ?? 0;
            $TOTAL_GROSS += $list->PAID_AMOUNT + $list->TAX_AMOUNT ?? 0;
            $TOTAL_PF += $list->DOCTOR_PF ?? 0;
            $TOTAL_NET += $list->PAID_AMOUNT ?? 0 + $list->TAX_AMOUNT ?? 0 - $list->DOCTOR_PF ?? 0;

            $rowData = [
                'NO'            => $running,
                'DATE'          => date('M/d/Y', strtotime($list->AR_DATE)),
                'LHIO'          => " ". $list->AR_NO,
                'NAME'          => $list->CONTACT_NAME,
                'CON'           => OtherServices::formatDates($list->CONFINE_PERIOD),
                'NOT'           => $list->HEMO_TOTAL,
                'FIRST_CASE'    => $list->P1_TOTAL,
                'D_PAID'        => $list->PAID_DATE ? date('M/d/Y', strtotime($list->PAID_DATE)) : '',
                'OR'            => $list->OR_NUMBER ?? '',
                'WTAX'          => $list->TAX_AMOUNT > 0 ? $list->TAX_AMOUNT : 0,
                'PAID'          => $list->PAID_AMOUNT > 0 ? $list->PAID_AMOUNT : 0,
                'GROSS'         => $list->PAID_AMOUNT > 0 ?  $list->PAID_AMOUNT + $list->TAX_AMOUNT  : 0,
                'PF'            => $list->DOCTOR_PF > 0 ? $list->DOCTOR_PF : 0,
                'NET'           => $list->DOCTOR_PF > 0 ?  $list->PAID_AMOUNT + $list->TAX_AMOUNT - $list->DOCTOR_PF : 0,
                'STATUS'        => $list->DOCTOR_PF > 0 ? ($list->DOCTOR_PF_BALANCE > 0 ? 'Not Paid' : 'Paid') : ' '
            ];

            $finalData[] = array_values($rowData);
        }



        $rowData = [
            'NO'            => '',
            'DATE'          => '',
            'LHIO'          => '',
            'NAME'          => '',
            'CON'           => '',
            'NOT'           => '',
            'FIRST_CASE'    => '',
            'D_PAID'        => '',
            'OR'            => '',
            'WTAX'          => '',
            'PAID'          => '',
            'GROSS'         => '',
            'PF'            => '',
            'NET'           => '',
            'STATUS'        => '',
        ];

        $finalData[] = array_values($rowData);

        $rowData = [
            'NO'            => '',
            'DATE'          => '',
            'LHIO'          => '',
            'NAME'          => '',
            'CON'           => '',
            'NOT'           => $TOTAL_NOT,
            'FIRST_CASE'    => $TOTAL_AMOUNT,
            'D_PAID'        => '',
            'OR'            => '',
            'WTAX'          => $TOTAL_WTAX,
            'PAID'          => $TOTAL_PAID,
            'GROSS'         => $TOTAL_GROSS,
            'PF'            => $TOTAL_PF,
            'NET'           => $TOTAL_NET,
            'STATUS'        => '',
        ];

        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}

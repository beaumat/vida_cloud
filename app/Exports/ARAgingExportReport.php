<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ARAgingExportReport implements FromCollection, ShouldAutoSize
{

    protected  $dataList = [];
    protected  bool $isSummary;
    public function __construct($dataList, bool $isSummary)
    {
        $this->dataList = $dataList;
        $this->isSummary = $isSummary;
    }
    private function SetSummary(string $CUSTOMER, string $CURRENT, string $N1_30, string $N31_60, string $N61_90, string $N90_OVER, string $BALANCE)
    {

        $headers = [
            'CUSTOMER'  => $CUSTOMER,
            'CURRENT'   => $CURRENT,
            '1-30'      => $N1_30,
            '31-60'     => $N31_60,
            '61-90'     => $N61_90,
            '90-OVER'   => $N90_OVER,
            'BALANCE'   => $BALANCE
        ];
        return array_values($headers);
    }
    public function SetDetails1(string $DATE, string $REFERENCE, string $CUSTOMER, string $TERMS, string $DUE_DATE, string $AGING, string $OPEN_BALANCE, string $LOCATION)
    {
        $rowData = [
            'DATE'=> $DATE,
            'REFERENCE'        => $REFERENCE,
            'CUSTOMER'         => $CUSTOMER,
            'TERMS'            => $TERMS,
            'DUE-DATE'        => $DUE_DATE,
            'AGING'           => $AGING,
            'OPEN-BALANCE'    =>  $OPEN_BALANCE,
            'LOCATION'        => $LOCATION
            ];

        return array_values($rowData);
    }

  public function SetDetails(
    string $INVOICE_DATE = '',
    string $DUE_DATE = '',
    string $INVOICE_NUMBER = '',
    string $INVOICE_REFERENCE = '',
    string $CURRENT = '0.00',
    string $LESS_1_MONTH = '0.00',
    string $ONE_MONTH = '0.00',
    string $TWO_MONTHS = '0.00',
    string $THREE_MONTHS = '0.00',
    string $OLDER = '0.00',
    string $TOTAL = '0.00'
) {
    $TOTAL = number_format(
        (float) str_replace(',', '', $CURRENT) +
        (float) str_replace(',', '', $LESS_1_MONTH) +
        (float) str_replace(',', '', $ONE_MONTH) +
        (float) str_replace(',', '', $TWO_MONTHS) +
        (float) str_replace(',', '', $THREE_MONTHS) +
        (float) str_replace(',', '', $OLDER),
        2
    );

    return [
        $INVOICE_DATE,
        $DUE_DATE,
        $INVOICE_NUMBER,
        $INVOICE_REFERENCE,
        number_format((float) str_replace(',', '', $CURRENT), 2),
        number_format((float) str_replace(',', '', $LESS_1_MONTH), 2),
        number_format((float) str_replace(',', '', $ONE_MONTH), 2),
        number_format((float) str_replace(',', '', $TWO_MONTHS), 2),
        number_format((float) str_replace(',', '', $THREE_MONTHS), 2),
        number_format((float) str_replace(',', '', $OLDER), 2),
        $TOTAL,
    ];
}
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $finalData[] = [
    'Invoice Date',
    'Due Date',
    'Invoice Number',
    'Invoice Reference',
    'Current',
    '< 1 Month',
    '1 Month',
    '2 Months',
    '3 Months',
    'Older',
    'Total'
];

        if ($this->isSummary) {
            $finalData[] = $this->SetSummary('CUSTOMER', 'CURRENT', '1-30', '1-60', '61-90', '90-OVER', 'BALANCE');
            //  SUMMARY START
            $DUE_CURRENT = 0;
            $DUE_1_30 = 0;
            $DUE_31_60 = 0;
            $DUE_61_90 = 0;
            $DUE_90_OVER = 0;
            $BALANCE = 0;

            foreach ($this->dataList as $list) {
                $finalData[] = $this->SetSummary(
                    $list->CONTACT_NAME,
                    number_format($list->DUE_CURRENT, 2),
                    number_format($list->DUE_1_30, 2),
                    number_format($list->DUE_31_60, 2),
                    number_format($list->DUE_61_90, 2),
                    number_format($list->DUE_90_OVER, 2),
                    number_format($list->BALANCE, 2)
                );
                $DUE_CURRENT = $DUE_CURRENT + $list->DUE_CURRENT;
                $DUE_1_30 = $DUE_1_30 + $list->DUE_1_30;
                $DUE_31_60 = $DUE_31_60 + $list->DUE_31_60;
                $DUE_61_90 = $DUE_61_90 + $list->DUE_61_90;
                $DUE_90_OVER = $DUE_90_OVER + $list->DUE_90_OVER;
                $BALANCE = $BALANCE + $list->BALANCE;
            }

            $finalData[] =   $this->SetSummary('', '', '', '', '', '', '');
            $finalData[] =   $this->SetSummary(
                number_format($DUE_CURRENT, 2),
                number_format($DUE_1_30, 2),
                number_format($DUE_31_60, 2),
                number_format($DUE_61_90, 2),
                number_format($DUE_61_90, 2),
                number_format($DUE_90_OVER, 2),
                number_format($BALANCE, 2)
            );
            // SUMMARY END

        } else {

        $finalData[] = ['AR Aging Report', '', '', '', '', '', '', '', '', '', ''];
$finalData[] = ['', '', '', '', '', '', '', '', '', '', ''];

$finalData[] = [
    'As of Date:',
    date('m/d/Y', strtotime($this->DATE ?? now())),
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    'Location:',
    $this->LOCATION_NAME ?? ''
];

$finalData[] = ['', '', '', '', '', '', '', '', '', '', ''];

$finalData[] = [
    'Invoice Date',
    'Due Date',
    'Invoice Number',
    'Invoice Reference',
    'Current',
    '< 1 Month',
    '1 Month',
    '2 Months',
    '3 Months',
    'Older',
    'Total'
];

$currentContact = null;
$currentDate = null;

foreach ($this->dataList as $list) {

    $rowDate = date('Y-m-d', strtotime($list->DATE));

    // Patient name row
    if ($currentContact !== $list->CONTACT_ID) {
        $currentContact = $list->CONTACT_ID;
        $currentDate = null;

        $finalData[] = [
            $list->CONTACT_NAME,
            '', '', '', '', '', '', '', '', '', ''
        ];
    }

    // Distinct invoice date row under patient
    if ($currentDate !== $rowDate) {
        $currentDate = $rowDate;

        $finalData[] = [
            date('d M Y', strtotime($rowDate)),
            '', '', '', '', '', '', '', '', '', ''
        ];
    }

    $current = 0;
    $less1Month = 0;
    $oneMonth = 0;
    $twoMonths = 0;
    $threeMonths = 0;
    $older = 0;

    if ($list->AGING <= 0) {
        $current = $list->BALANCE_DUE;
    } elseif ($list->AGING <= 30) {
        $less1Month = $list->BALANCE_DUE;
    } elseif ($list->AGING <= 60) {
        $oneMonth = $list->BALANCE_DUE;
    } elseif ($list->AGING <= 90) {
        $twoMonths = $list->BALANCE_DUE;
    } elseif ($list->AGING <= 120) {
        $threeMonths = $list->BALANCE_DUE;
    } else {
        $older = $list->BALANCE_DUE;
    }

    // Invoice row
    $finalData[] = [
        '',
        date('d M Y', strtotime($list->DUE_DATE)),
        $list->CODE,
        $list->REFERENCE ?? '',
        number_format($current, 2),
        number_format($less1Month, 2),
        number_format($oneMonth, 2),
        number_format($twoMonths, 2),
        number_format($threeMonths, 2),
        number_format($older, 2),
        number_format($list->BALANCE_DUE, 2),
    ];
}
        }
        return collect($finalData);
    }
}

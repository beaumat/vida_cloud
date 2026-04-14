<?php

namespace App\Exports;

use App\Services\OtherServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentPeriodExport implements FromCollection, ShouldAutoSize
{

    protected $GROSS_TOTAL;
    protected $dataList = [];

    public function __construct($dataList = [], $GROSS_TOTAL)
    {
        $this->dataList = $dataList;
        $this->GROSS_TOTAL = $GROSS_TOTAL;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $finalData = [];

        $headers = [
            'DATE_TRANSMIT' => 'DATE TRANSMITTED',
            'LHIO_NO' => 'LHIO No.',
            'PATIENT' => 'PATIENT',
            'ADMITTED' => 'ADMITTED',
            'DISCHARGE' => 'DISCHARED',
            'NO_OF_TREATMENT' => 'NO. OF TREATMENT',
            'CONFINEMENT_PERIOD' => 'CONFINEMENT PERIOD',
            'FIRST_CASE_AMOUNT' => 'FIRST CASE',
            'PAID_AMOUNT' => 'PAID AMOUNT',
            'WTAX_AMOUNT' => 'WTAX AMOUNT',
            'PF_AMOUNT' => 'PF AMOUNT',
            'DOCTOR_NAME' => 'DOCTOR NAME'
        ];
        $finalData[] = array_values($headers);
        $TEMP_NAME = "";
        $TOTAL_INVOICE = 0;
        $TOTAL_TAX = 0;
        $TOTAL_PAID = 0;
        $TOTAL_PF = 0;




        foreach ($this->dataList as $list) {
            $code = $list->AR_NO;
            $rows = [
                'DATE_TRANSMIT' => \Carbon\Carbon::parse($list->AR_DATE)->format('m/d/Y'),
                'LHIO_NO' => " $code",
                'PATIENT' => $list->PATIENT_NAME,
                'ADMITTED' => \Carbon\Carbon::parse($list->DATE_ADMITTED)->format('m/d/Y'),
                'DISCHARGE' => \Carbon\Carbon::parse($list->DATE_DISCHARGED)->format('m/d/Y'),
                'NO_OF_TREATMENT' => $list->HEMO_TOTAL,
                'CONFINEMENT_PERIOD' => OtherServices::formatDates($list->CONFINE_PERIOD),
                'FIRST_CASE_AMOUNT' => $list->INVOICE_AMOUNT,
                'PAID_AMOUNT' => $list->PAYMENT_AMOUNT,
                'WTAX_AMOUNT' => $list->TAX_AMOUNT,
                'PF_AMOUNT' => $list->BILL_AMOUNT,
                'DOCTOR_NAME' => $list->DOCTOR_NAME
            ];
            $finalData[] = array_values($rows);

            $TOTAL_INVOICE += $list->INVOICE_AMOUNT;
            $TOTAL_PAID += $list->PAYMENT_AMOUNT;
            $TOTAL_TAX += $list->TAX_AMOUNT;
            $TOTAL_PF += $list->BILL_AMOUNT;


        }


        $rowend = [
            'DATE_TRANSMIT' => '',
            'LHIO_NO' => '',
            'PATIENT' => '',
            'ADMITTED' => '',
            'DISCHARGE' => '',
            'NO_OF_TREATMENT' => '',
            'CONFINEMENT_PERIOD' => 'Grand Total',
            'FIRST_CASE_AMOUNT' => $TOTAL_INVOICE,
            'PAID_AMOUNT' => $TOTAL_PAID,
            'WTAX_AMOUNT' => $TOTAL_TAX,
            'PF_AMOUNT' => $TOTAL_PF,
            'DOCTOR_NAME' => ''
        ];
        $finalData[] = array_values($rowend);

        $rowend = [
            'DATE_TRANSMIT' => '',
            'LHIO_NO' => '',
            'PATIENT' => '',
            'ADMITTED' => '',
            'DISCHARGE' => '',
            'NO_OF_TREATMENT' => '',
            'CONFINEMENT_PERIOD' => 'Difference',
            'FIRST_CASE_AMOUNT' => $TOTAL_INVOICE - $this->GROSS_TOTAL,
            'PAID_AMOUNT' => '',
            'WTAX_AMOUNT' => '',
            'PF_AMOUNT' => '',
            'DOCTOR_NAME' => ''
        ];
        $finalData[] = array_values($rowend);


        return collect($finalData);
    }
}

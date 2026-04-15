<?php

namespace App\Exports;

use App\Services\ServiceChargeServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesPatientBalanceExport implements FromCollection, ShouldAutoSize
{
    protected int $PATIENT_ID;
    protected int $LOCATION_ID;
    protected string $DATE_FROM;
    protected string $DATE_TO;

    private $serviceChargeServices;
    public function __construct(ServiceChargeServices  $serviceChargeServices, int $PATIENT_ID, int $LOCATION_ID, string $DATE_FROM, string $DATE_TO)
    {
        $this->PATIENT_ID  = $PATIENT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->DATE_FROM = $DATE_FROM;
        $this->DATE_TO = $DATE_TO;
        $this->serviceChargeServices = $serviceChargeServices;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $BALANCE = 0;
        // balanceList
        $dataList = $this->serviceChargeServices->balanceList($this->PATIENT_ID, $this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);
        $headers = [
            'PN'        => 'PATIENT NAME',
            'IN'        => 'ITEM NAME',
            'SC_DATE'   => '(SC)DATE',
            'SC_CODE'   => '(SC)CODE',
            'AMOUNT'    => 'AMOUNT',
            'PAID'      => 'PAID',
            'BALANCE'   => 'BALANCE'
        ];
        $BALANCE = 0;
        $finalData = [];
        $finalData[] = array_values($headers);

        foreach ($dataList as $list) {
            $rowData = [
                'PN'        =>   $list->CONTACT_NAME,
                'IN'        => $list->ITEM_NAME,
                'SC_DATE'   => date('m/d/Y', strtotime($list->DATE)),
                'SC_CODE'   => $list->CODE,
                'AMOUNT'    => $list->AMOUNT,
                'PAID'      => $list->PAID_AMOUNT,
                'BALANCE'   => $list->BALANCE
            ];

            $BALANCE = $BALANCE + $list->BALANCE ?? 0;

            $finalData[] = array_values($rowData);
        }

        $rowData = [
            'PN'        => '',
            'IN'        => '',
            'SC_DATE'   => '',
            'SC_CODE'   => '',
            'AMOUNT'    => '',
            'PAID'      => '',
            'BALANCE'   => ''
        ];
        $finalData[] = array_values($rowData);

        $rowData = [
            'PN'        => '',
            'IN'        => '',
            'SC_DATE'   => '',
            'SC_CODE'   => '',
            'AMOUNT'    => '',
            'PAID'      => '',
            'BALANCE'   => $BALANCE
        ];
        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}

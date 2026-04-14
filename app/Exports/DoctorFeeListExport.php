<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DoctorFeeListExport implements FromCollection, ShouldAutoSize
{

    protected $doctorList;
    protected int $row;
    protected $headerList;
    protected $totalList;

    public function __construct(int $row, $headerList, $totalList, $doctorList)
    {
        $this->row = $row;
        $this->headerList = $headerList;
        $this->totalList = $totalList;
        $this->doctorList = $doctorList;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $headerPeriod = [];
        $headerPeriod[]  = 'Doctor List';
        foreach ($this->headerList as $list) {
            $headerPeriod[] = date('m/d/Y', strtotime($list['DATE_FROM'])) . '-' . date('m/d/Y', strtotime($list['DATE_TO']));
        }
        $headerPeriod[]  = 'Sub Total';
        $headerPeriod[]  = 'Remaining Bal.';
        $headerPeriod[]  = 'Grand Total';
        $finalData[] = array_values($headerPeriod);


        $recept = [];
        $recept[] = "";
        foreach ($this->headerList as $list) {
            $recept[] = 'OR Date: ' . date('m/d/Y', strtotime($list['DATE'])) . '- OR#: ' . $list['RECEIPT_NO'];
        }
        $recept[] = "";
        $recept[] = "";
        $recept[] = "";
        $finalData[] = array_values($recept);

        $balancetotal = 0;
        $grandtotal = 0;

        foreach ($this->doctorList as $list) {
            $line = [];
            $line[] = $list['DOCTOR_NAME'];
            $total = 0;

            for ($n = 1; $n <= $this->row; $n++) {
                $total = $total + $list[$n] ?? 0;
                $line[] = (float) $list[$n];
            }
            $line[] =  (float) $total;
            $balancetotal = $balancetotal + $list['BALANCE_TOTAL'] ?? 0.00;
            $line[] = (float) $list['BALANCE_TOTAL'] ?? 0.00;
            $grandtotal = $grandtotal + ($total + $list['BALANCE_TOTAL']) ?? 0.00;
            $line[] = (float) $total + (float) $list['BALANCE_TOTAL'] ?? 0.00;
            $finalData[] = array_values($line);
        }


        $linetotal = [];

        if ($grandtotal > 0 || $balancetotal > 0) {
            $linetotal[] = 'Total';
            for ($n = 1; $n <= $this->row; $n++) {
                if (isset($this->totalList[$n])) {
                    $linetotal[] = (float) $this->totalList[$n] ?? 0;
                }
            }

            $linetotal[] = (float) $grandtotal ?? 0;
            $linetotal[] = (float) $balancetotal ?? 0;
            $linetotal[] = (float) $grandtotal ?? 0 + (float) $balancetotal ?? 0;
            $finalData[] = array_values($linetotal);
        }







        return collect($finalData);
    }
}

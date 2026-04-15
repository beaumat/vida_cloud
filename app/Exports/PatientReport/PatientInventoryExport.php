<?php

namespace App\Exports\PatientReport;

use Maatwebsite\Excel\Concerns\FromCollection;

class PatientInventoryExport implements FromCollection
{

    protected  $dataList = [];
    protected $headers = [];
    
    public function __construct($dataList, array $headers = [])
    {
        $this->dataList = $dataList;
        $this->headers = $headers;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       $finalData = [];
 
   
        $finalData[] = array_values($this->headers);

        foreach ($this->dataList as $list) {


            $rowData = [
                'ACCOUNT_NAME'  => $list->ACCOUNT_TITLE,
                'DEBIT'         => $list->TX_DEBIT,
                'CREDIT'        => $list->TX_CREDIT
            ];

   
            $finalData[] = array_values($rowData);
        }


       


        return collect($finalData);
    }
}

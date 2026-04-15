<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeListExport implements FromCollection, ShouldAutoSize
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
            'ACCOUNT_NO'          => 'ACCOUNT_NO',
            'NAME'                => 'NAME',
            'POSTAL_ADDRESS'      => 'POSTAL_ADDRESS',
            'MOBILE_NO'           => 'MOBILE_NO',
            'EMAIL'               => 'EMAIL',
            'PIN'                 => 'PIN',
            'LOCATION'            => 'LOCATION',
            'INACTIVE'            => 'STATUS',

        ];

        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {

            $rowData = [
                'ACCOUNT_NO'        => $list->ACCOUNT_NO,
                'NAME'              => $list->NAME,
                'POSTAL_ADDRESS'    => $list->POSTAL_ADDRESS,
                'MOBILE_NO'         => $list->MOBILE_NO,
                'EMAIL'             => $list->EMAIL,
                'PIN'               => $list->PIN,
                'LOCATION'          => $list->LOCATION,
                'INACTIVE'          => $list->INACTIVE ? 'IN-ACTIVE' : 'ACTIVE'
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

<?php

namespace App\Exports;

use App\Services\HemoServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TreatmentListExport implements FromCollection, ShouldAutoSize
{

    private $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $dataList = $this->data;

        $headers = [
            '#' =>  '#',
            'NO'            => 'NO',
            'DATE'          => 'DATE',
            'PATIENT_NAME'  => 'PATIENT_NAME',
            'W'             => 'W',
            'BP'            => 'BP',
            'HR'            => 'HR',
            '02'            => 'O2',
            'TMP'           => 'TMP',
            'START'         => 'START',
            'END'           => 'END',
            'IC'            => 'IC',
            'LOCATION'      => 'LOCATION',
            'STATUS'        => 'STATUS',
            'SC'            => 'SC',
            'NURSE'         => 'NURSE ON DUTY',
            'ACCESS_TYPE'   => 'ACCESS_TYPE'
        ];
        $NUMBER = 0;
        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row
        foreach ($dataList as $list) {
            $NUMBER++;
            $rowData = [
                '#'             => $NUMBER,
                'NO'            => $list->CODE,
                'DATE'          => $list->DATE,
                'PATIENT_NAME'  => $list->CONTACT_NAME,
                'W'             => $list->PRE_WEIGHT . ' | ' .  $list->POST_WEIGHT,
                'BP'            => $list->PRE_BLOOD_PRESSURE . '/' . $list->PRE_BLOOD_PRESSURE2 . ' | ' . $list->POST_BLOOD_PRESSURE . '/' . $list->POST_BLOOD_PRESSURE2,
                'HR'            => $list->PRE_HEART_RATE . '|' . $list->POST_HEART_RATE,
                '02'            => $list->PRE_O2_SATURATION . '|' . $list->POST_O2_SATURATION,
                'TMP'           => $list->PRE_TEMPERATURE . '|' .  $list->POST_TEMPERATURE,
                'START'         => $list->TIME_START ? \Carbon\Carbon::parse($list->TIME_START)->format('h:i A') : '',
                'END'           => $list->TIME_END ? \Carbon\Carbon::parse($list->TIME_END)->format('h:i A') : '',
                'IC'            => $list->IS_INCOMPLETE ? 'Yes' : ' ',
                'LOCATION'      => $list->LOCATION_NAME,
                'STATUS'        => $list->STATUS,
                'SC'            => $list->IS_SC == true ? 'Yes' : 'No',
                'NURSE_NAME'    => $list->NURSE_NAME,
                'ACCESS_TYPE'   => $list->ACCESS_TYPE
            ];

            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}

<?php
namespace App\Exports\PatientReport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PhilhealthAvailmentExport implements FromCollection, ShouldAutoSize
{
    protected $dataList;
    protected $header;
    public function __construct($dataList, $header)
    {
        $this->dataList = $dataList;
        $this->header   = $header;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $finalData = [];

        $finalData[] = array_values($this->header);

        $finalData[] = array_values($this->dataList);
        
        return collect($finalData);
    }
}

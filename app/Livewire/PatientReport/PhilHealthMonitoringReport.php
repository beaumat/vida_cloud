<?php

namespace App\Livewire\PatientReport;

use App\Exports\PhilhealthMonitoringExport;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Philhealth Monitorning Report')]
class PhilHealthMonitoringReport extends Component
{
    public int $YEAR;
    public int $MONTH;
    public $monthList = [];
    public $yearList = [];
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public  function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList  = $this->locationServices->getList();
        $this->YEAR = $this->dateServices->NowYear();
        $this->MONTH = $this->dateServices->NowMonth();
        $this->monthList = $this->dateServices->MonthList();
        $this->yearList = $this->dateServices->YearList();
    }
    public function generate()
    {
        $this->dataList = $this->philHealthServices->getMonitor($this->YEAR, $this->MONTH, $this->LOCATION_ID);
    }
    public function export()
    {
        $this->dataList = [];

        $dataExport = $this->philHealthServices->getMonitor($this->YEAR, $this->MONTH, $this->LOCATION_ID);
        return Excel::download(new PhilhealthMonitoringExport(
            $dataExport,
        ), 'philhealth-monitoring-export.xlsx');
 
    }
    public function render()
    {
        return view('livewire.patient-report.phil-health-monitoring-report');
    }
}

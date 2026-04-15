<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\PatientStatusServices;
use Livewire\Component;

class SalesCollection extends Component
{
    public $locationList = [];
    public bool $isShow = false;
    private $patientStatusServices;
    private $dateServices;


    public $monthlyList = [];
    public $yearList = [];
    public int $month = 0;
    public int $year = 0;
    public function boot(PatientStatusServices $patientStatusServices, DateServices $dateServices)
    {
        $this->dateServices = $dateServices;
        $this->patientStatusServices = $patientStatusServices;
    }
    public function mount()
    {
        $this->monthlyList = $this->dateServices->FullMonthList();
        $this->yearList = $this->dateServices->YearList();
        $this->month = $this->dateServices->NowMonth();
        $this->year = $this->dateServices->NowYear();
    }
    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {      
            $this->locationList = $this->isShow ? $this->patientStatusServices->getSalesColleciton($this->month, $this->year) : [];
       
        return view('livewire.dashboard-page.sales-collection');
    }
}

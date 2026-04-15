<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\PatientStatusServices;
use Livewire\Component;

class PatientStatus extends Component
{

    public $locationList = [];
    private $patientStatusServices;
    private $dateServices;
    public $monthlyList = [];
    public $yearList = [];
    public int $month = 0;
    public int $year = 0;
    public function boot(PatientStatusServices $patientStatusServices, DateServices $dateServices)
    {
        $this->patientStatusServices = $patientStatusServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->monthlyList = $this->dateServices->FullMonthList();
        $this->yearList = $this->dateServices->YearList();
        $this->month = $this->dateServices->NowMonth();
        $this->year = $this->dateServices->NowYear();

    }
    public bool $isShow = false;
    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        $this->locationList = $this->isShow ? $this->patientStatusServices->getList($this->month, $this->year) : [];

        return view('livewire.dashboard-page.patient-status');
    }
}

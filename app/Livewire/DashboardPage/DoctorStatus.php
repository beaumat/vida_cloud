<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\PatientStatusServices;
use Livewire\Component;

class DoctorStatus extends Component
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


    }
    public bool $isShow = false;
    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        $this->locationList = $this->isShow ? $this->patientStatusServices->getDoctorPF() : [];

        return view('livewire.dashboard-page.doctor-status');
    }
}

<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\PatientStatusServices;
use Livewire\Component;

class TreatmentSummaryStatus extends Component
{
    public $locationList = [];
    private $dateServices;
    public $monthlyList = [];
    public $yearList = [];
    public int $month = 0;
    public int $year = 0;
    public bool $isShow = false;
    private $patientStatusServices;
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
        $this->isShow = false;
    }

    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        $this->locationList = $this->isShow ? $this->patientStatusServices->getTreatmentSummaryList($this->month, $this->year) : [];
        return view('livewire.dashboard-page.treatment-summary-status');
    }
}

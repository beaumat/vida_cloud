<?php

namespace App\Livewire\DashboardPage;

use App\Services\PatientStatusServices;
use Livewire\Component;

class ReceivableStatus extends Component
{
    public $locationList = [];
    public bool $isShow = false;
    private $patientStatusServices;
    public function boot(PatientStatusServices $patientStatusServices)
    {
        $this->patientStatusServices = $patientStatusServices;
    }

    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {

        $this->locationList = $this->isShow ? $this->patientStatusServices->getReceivableAging() : [];

        return view('livewire.dashboard-page.receivable-status');
    }
}

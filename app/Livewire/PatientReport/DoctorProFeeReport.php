<?php

namespace App\Livewire\PatientReport;

use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Doctors PF Reports')]
class DoctorProFeeReport extends Component
{
    public int $LOCATION_ID;
    public $locationList = [];
    public $doctorList = [];
    private $doctorPFServices;
    private $locationServices;
    private $userServices;


    public function boot(DoctorPFServices $doctorPFServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->doctorPFServices = $doctorPFServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function Generate()
    {
        $data = $this->doctorPFServices->getDoctorList($this->LOCATION_ID);

        $this->doctorList = $data;
    }

    public function openList(int $DOCTOR_ID)
    {
        $data = [
            'DOCTOR_ID' => $DOCTOR_ID,
            'LOCATION_ID' => $this->LOCATION_ID 
        ];
        $this->dispatch('pf-open-list', result: $data);
    }
    public function printList()
    {   
        
    }
    public function render()
    {
        return view('livewire.patient-report.doctor-pro-fee-report');
    }
}

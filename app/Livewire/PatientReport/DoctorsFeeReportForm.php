<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DoctorPFServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorsFeeReportForm extends Component
{

    public bool $isDisabled = false;
    public bool $showModal = false;
    public $dataList = [];
    public string $DOCTOR_NAME;
    private $doctorPFServices;
    private $contactServices;
    public int $DOCTOR_ID;
    public int $LOCATION_ID;
    public function boot(
        DoctorPFServices $doctorPFServices,
        ContactServices $contactServices
    ) {
        $this->doctorPFServices = $doctorPFServices;
        $this->contactServices = $contactServices;
    }
    #[On('pf-open-list')]
    public function openModal($result)
    {
        $this->LOCATION_ID = $result['LOCATION_ID'];
        $this->DOCTOR_ID =  $result['DOCTOR_ID'];
        
        $contactData = $this->contactServices->get($this->DOCTOR_ID, 4);

        $this->DOCTOR_NAME =  $contactData->PRINT_NAME_AS;
        $this->dataList = $this->doctorPFServices->PatientGenerate($this->LOCATION_ID, $this->DOCTOR_ID);
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.patient-report.doctors-fee-report-form');
    }
}

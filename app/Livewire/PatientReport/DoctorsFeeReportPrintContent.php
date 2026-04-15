<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use Livewire\Component;

class DoctorsFeeReportPrintContent extends Component
{
    public int $DOCTOR_ID = 0;
    public int $LOCATION_ID = 0;
    public int $TOTAL_TREATMENT = 0;
    public int $n = 0;
    public float $TOTAL_AMOUNT = 0;
    public $dataList;
    public string $DOCTOR_NAME;
    public string $ADMINISTRATOR_NAME;
    public string $USER_NAME;
    private $doctorPFServices;
    private $contactServices;
    private $locationServices;
    public function boot(DoctorPFServices $doctorPFServices, ContactServices $contactServices, LocationServices $locationServices)
    {
        $this->doctorPFServices = $doctorPFServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }

    public function mount(int $DOCTOR_ID, int $LOCATION_ID)
    {
        $this->DOCTOR_ID = $DOCTOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;

        $contactData = $this->contactServices->get($this->DOCTOR_ID, 4);

        $this->DOCTOR_NAME =  strtoupper($contactData->PRINT_NAME_AS);
        $this->dataList = $this->doctorPFServices->PatientGenerate($this->LOCATION_ID, $this->DOCTOR_ID);

        $locDate = $this->locationServices->get($this->LOCATION_ID);
        if ($locDate) {

            $conPHIC = $this->contactServices->get($locDate->PHIC_INCHARGE_ID ?? 0, 2); // Employee
            if ($conPHIC) {
                $this->USER_NAME = strtoupper($conPHIC->PRINT_NAME_AS) ?? '';
            }
            // $HCI_MANAGER_ID
            $conMgr = $this->contactServices->get($locDate->HCI_MANAGER_ID ?? 0, 2); // Employee
            if ($conMgr) {
                $this->ADMINISTRATOR_NAME = strtoupper($conMgr->PRINT_NAME_AS) ?? '';
            }
        }
    }
    public function render()
    {
        return view('livewire.patient-report.doctors-fee-report-print-content');
    }
}

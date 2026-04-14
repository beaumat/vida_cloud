<?php
namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Annex C Print")]

class PhilhealthAnnexOnePrint extends Component
{

    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public string $LOGO_FILE;
    public string $EMPLOYEE_NAME;
    public string $EMPLOYEE_POSITION;
    public string $MANAGER_NAME;
    public string $APPROVED_BY;

    public $dataList = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $contactServices;
    public function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices, ContactServices $contactServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
        $this->dateServices       = $dateServices;
        $this->contactServices    = $contactServices;
    }

    public function mount(int $locationid, int $year, int $month)
    {
        $this->dataList = $this->philHealthServices->GenerateAnnex($year, $month, $locationid);


        $locData = $this->locationServices->get($locationid);

        if ($locData) {
            $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
            $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
            $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
            $this->LOGO_FILE       = $locData->LOGO_FILE ?? '';
            $this->APPROVED_BY     = strtoupper($locData->MEDICAL_DIRECTOR ?? '') ?? '';
            $conPHIC               = $this->contactServices->get($locData->PREPARED_BY_ID ?? Auth()->user()->contact_id, 2); // Employee
            if ($conPHIC) {
                $this->EMPLOYEE_POSITION = "Billing Clerk";
                $this->EMPLOYEE_NAME     = strtoupper($conPHIC->PRINT_NAME_AS) ?? '';
            }

            $conMgr = $this->contactServices->get($locData->HCI_MANAGER_TREATMENT_ID, 2); // Employee
            if ($conMgr) {
                $this->MANAGER_NAME = strtoupper($conMgr->PRINT_NAME_AS) ?? '';
            }
        }
          $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.patient-report.philhealth-annex-one-print');
    }
}

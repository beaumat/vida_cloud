<?php

namespace App\Livewire\PatientReport;

use App\Exports\GuaranteeLetterExport;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Guarantee Letter Report')]
class GuaranteeLetterReport extends Component
{

    public bool $refreshComponent = false;
    public int $PATIENT_ID = 0;
    public int $LOCATION_ID;

    public float $BALANCE;
    public $locationList = [];
    public $patientList = [];
    public $dataList = [];
    public bool $includeZero = false;
    private $patientPaymentServices;
    private $dateServices;
    private $userServices;
    private $locationServices;
    private $contactServices;
    public function boot(
        ContactServices $contactServices,
        PatientPaymentServices $patientPaymentServices,
        DateServices $dateServices,
        UserServices $userServices,
        LocationServices $locationServices
    ) {

        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
        $this->userServices = $userServices;
        $this->contactServices = $contactServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function export()
    {

        $dataList = $this->patientPaymentServices->GetAssistanceAll(
            $this->PATIENT_ID,
            $this->LOCATION_ID,
            $this->includeZero
        );
        return Excel::download(new GuaranteeLetterExport(
            $dataList

        ), 'GL-Export.xlsx');
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();

        $this->updatedlocationId();
    }
    public function updatedlocationId()
    {

        try {

            $this->patientList = $this->contactServices->getPatientList($this->LOCATION_ID);
            $this->PATIENT_ID = 0;
            $this->refreshComponent = $this->refreshComponent ? false : true;

            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }

    public function generate()
    {
        $this->BALANCE = 0;
        $this->dataList = $this->patientPaymentServices->GetAssistanceAll(
            $this->PATIENT_ID,
            $this->LOCATION_ID,
            $this->includeZero
        );

    }
    public function render()
    {
        return view('livewire.patient-report.guarantee-letter-report');
    }
}

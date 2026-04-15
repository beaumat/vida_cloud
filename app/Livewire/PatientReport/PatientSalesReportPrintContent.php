<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Component;

class PatientSalesReportPrintContent extends Component
{

    public bool $refreshComponent = false;
    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public $patientList = [];
    public string $DATE_COLLECTION_FROM;
    public string $DATE_COLLECTION_TO;
    public string $DATE_TRANSACTION_FROM;
    public string $DATE_TRANSACTION_TO;
    public string $tempName;
    public bool $is_add;
    public float $running_balance;

    public string $sc_code;
    public bool $is_code;
    private $locationServices;
    private $userServices;
    public $dataList = [];
    public $preDataList = [];
    public int $PREV_SC_ITEM_REF_ID = 0;
    public bool $not_to_charge = false;
    public float $TOTAL_CHARGE = 0;
    public float $TOTAL_PAID = 0;
    public float $CASH_AMOUNT;
    public float $PRE_COLLECTION;
    public float $PHILHEALTH_AMOUNT;
    public float $DSWD_AMOUNT;
    public float $LINGAP_AMOUNT;
    public float $PCSO_AMOUNT;
    public float $OTHER_GL_AMOUNT;
    public int $NO_OF_PATIENT = 0;
    public int $NO_OF_TREATMENT = 0;
    private $contactServices;
    private $patientReportServices;
    private $itemServices;
    public $filterPatient = [];
    public $selectedPatient = [];
    public $filterItem = [];
    public $selectedItem = [];

    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        ContactServices $contactServices,
        PatientReportServices $patientReportServices,
        ItemServices $itemServices
    ) {
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->contactServices = $contactServices;
        $this->patientReportServices = $patientReportServices;
        $this->itemServices = $itemServices;
    }

    public function mount($DATE_FROM, $DATE_TO, $LOCATION_ID)
    {
        $this->DATE_TRANSACTION_FROM = $DATE_FROM;
        $this->DATE_TRANSACTION_TO = $DATE_TO;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->showfilter();
    }

    public function showfilter()
    {

        $this->selectedItem = [];
        $this->selectedPatient = [];
        $this->shortFilter();
        // $this->filterPatient  = $this->contactServices->getPatientListViaReport($this->LOCATION_ID, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO);
        // $this->filterItem =  $this->patientReportServices->getItemListViaReport($this->LOCATION_ID, $this->DATE_TRANSACTION_FROM, $this->DATE_TRANSACTION_TO);

        $this->refreshComponent = $this->refreshComponent ? false : true;
    }

    public function shortFilter()
    {
        $this->NO_OF_PATIENT  = 0;
        $this->TOTAL_CHARGE = 0;
        $this->TOTAL_PAID = 0;
        $this->CASH_AMOUNT = 0;
        $this->PHILHEALTH_AMOUNT = 0;
        $this->PRE_COLLECTION = 0;
        $this->DSWD_AMOUNT = 0;
        $this->LINGAP_AMOUNT = 0;
        $this->PCSO_AMOUNT = 0;
        $this->OTHER_GL_AMOUNT = 0;
        $this->NO_OF_TREATMENT = 0;
        $this->DATE_COLLECTION_FROM  = '';
        $this->DATE_COLLECTION_TO = '';

        $this->dataList = $this->patientReportServices->generateSalesReportData2(

            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->LOCATION_ID,
            $this->selectedPatient,
            $this->selectedItem
        );

        $this->preDataList = $this->patientReportServices->generatePrevCollection2(
            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->LOCATION_ID,
            $this->selectedPatient,
            $this->selectedItem
        );

        foreach ($this->preDataList as $data) {
            $this->PRE_COLLECTION =  $this->PRE_COLLECTION + $data->PP_PAID ?? 0;
        }
    }
    public function render()
    {
        return view('livewire.patient-report.patient-sales-report-print-content');
    }
}

<?php
namespace App\Livewire\PatientReport;

use App\Exports\PatientSalesReportExport;
use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Sales Report - View')]
class PatientSalesReportView extends Component
{

    public bool $refreshComponent = false;
    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public $patientList  = [];
    public string $DATE_TRANSACTION_FROM;
    public string $DATE_TRANSACTION_TO;
    public string $tempName;
    public bool $is_add;
    public float $running_balance;

    public string $sc_code;
    public bool $is_code;
    private $locationServices;
    private $userServices;
    public $dataList                = [];
    public $preDataList             = [];
    public int $PREV_SC_ITEM_REF_ID = 0;
    public bool $not_to_charge      = false;
    public float $TOTAL_CHARGE      = 0;
    public float $TOTAL_PAID        = 0;
    public float $CASH_AMOUNT;
    public float $PRE_COLLECTION;
    public float $PRE_CASH_AMOUNT;
    public float $PHILHEALTH_AMOUNT;
    public float $DSWD_AMOUNT;
    public float $LINGAP_AMOUNT;
    public float $PCSO_AMOUNT;
    public float $OTHER_GL_AMOUNT;
    public float $OP_AMOUNT;
    public float $OVP_AMOUNT;

    public int $NO_OF_PATIENT   = 0;
    public int $NO_OF_TREATMENT = 0;
    private $contactServices;
    private $patientReportServices;

    public $filterPatient   = [];
    public $selectedPatient = [];
    public $filterItem      = [];
    public $selectedItem    = [];
    public $filterMethod    = [];
    public $selectedMethod  = [];
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        ContactServices $contactServices,
        PatientReportServices $patientReportServices,

    ) {
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
        $this->contactServices       = $contactServices;
        $this->patientReportServices = $patientReportServices;
    }
    public function updatedselectedPatient()
    {
        $this->shortFilter();
    }
    public function updatedselectedItem()
    {
        $this->shortFilter();
    }
    public function updatedselectedMethod()
    {
        $this->shortFilter();
    }
    public function mount($date_from, $date_to, $location_id, $patient = null, $item = null, $method = null)
    {

        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID  = $location_id;

        $this->DATE_TRANSACTION_FROM = $date_from;
        $this->DATE_TRANSACTION_TO   = $date_to;

        if ($patient) {
            $this->selectedPatient = $patient !== 'none' ? explode(',', $patient) : [];
        } else {
            $this->selectedPatient = [];
        }

        if ($item) {
            $this->selectedItem = $item !== 'none' ? explode(',', $item) : [];
        } else {
            $this->selectedItem = [];
        }
        if ($method) {
            $this->selectedMethod = $method !== 'none' ? explode(',', $method) : [];
        } else {
            $this->selectedMethod = [];
        }

        $this->dispatch('show-data');
    }
    #[On('show-data')]
    public function getData()
    {
        $this->shortFilter();
    }

    public function export()
    {
        return Excel::download(new PatientSalesReportExport(
            $this->dataList,
            $this->preDataList
        ), 'sales-report.xlsx');
    }

    public function shortFilter()
    {
        $this->NO_OF_PATIENT     = 0;
        $this->TOTAL_CHARGE      = 0;
        $this->TOTAL_PAID        = 0;
        $this->CASH_AMOUNT       = 0;
        $this->PHILHEALTH_AMOUNT = 0;
        $this->PRE_COLLECTION    = 0;
        $this->PRE_CASH_AMOUNT   = 0;
        $this->DSWD_AMOUNT       = 0;
        $this->LINGAP_AMOUNT     = 0;
        $this->PCSO_AMOUNT       = 0;
        $this->OTHER_GL_AMOUNT   = 0;
        $this->OP_AMOUNT         = 0;
        $this->OVP_AMOUNT        = 0;
        $this->NO_OF_TREATMENT   = 0;

        $this->dataList = $this->patientReportServices->generateSalesReportData(
            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->LOCATION_ID,
            $this->selectedPatient,
            $this->selectedItem,
            $this->selectedMethod
        );

        $this->preDataList = $this->patientReportServices->getPreviousCollection(
            $this->DATE_TRANSACTION_FROM,
            $this->DATE_TRANSACTION_TO,
            $this->LOCATION_ID,
            $this->selectedPatient,
            $this->selectedItem,
            $this->selectedMethod
        );

        foreach ($this->preDataList as $data) {

            switch ($data->PAYMENT_METHOD_ID) {
                case 1:
                    $this->PRE_CASH_AMOUNT = $this->PRE_CASH_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 92:
                    $this->DSWD_AMOUNT = $this->DSWD_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 93:
                    $this->LINGAP_AMOUNT = $this->LINGAP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 94:
                    $this->PCSO_AMOUNT = $this->PCSO_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 96:
                    $this->OTHER_GL_AMOUNT = $this->OTHER_GL_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 98:
                    $this->OP_AMOUNT = $this->OP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 97:
                    $this->OVP_AMOUNT = $this->OVP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                default:
                    # code...
                    break;
            }

            if ($data->PAYMENT_METHOD_ID == 1) {

            } else {
                $this->PRE_COLLECTION = $this->PRE_COLLECTION + $data->PP_PAID ?? 0;
            }
        }
    }
    public function print()
    {
        return redirect()->away(route('reportspatient_sales_report_print', []));
    }

    public function render()
    {
        return view('livewire.patient-report.patient-sales-report-view');
    }
}

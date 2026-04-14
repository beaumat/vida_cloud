<?php
namespace App\Livewire\PatientReport;

use App\Exports\DynamicExport;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title(('Annex C Report'))]
class PhilhealthAnnex extends Component
{

    public int $YEAR;
    public int $MONTH;
    public $monthList = [];
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList     = [];
    public $columnList   = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
        $this->dateServices       = $dateServices;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->YEAR        = $this->dateServices->NowYear();
        $this->MONTH       = $this->dateServices->NowMonth();

        $this->locationList = $this->locationServices->getList();
        $this->monthList    = $this->dateServices->MonthList();
    }
    public function updatedLOCATIONID()
    {
        $this->columnType = 0;
        $this->dataList   = [];

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedYEAR()
    {
        $this->columnType = 0;
        $this->dataList   = [];
    }
    public function updatedMONTH()
    {
        $this->columnType = 0;
        $this->dataList   = [];
    }
    public function generate()
    {
        $this->dataList = $this->philHealthServices->GenerateAnnex($this->YEAR, $this->MONTH, $this->LOCATION_ID);
    }

    private int $autoNumber = 0;
    private function setData($data)
    {
        $this->autoNumber++;

        $ref     = strtoupper(date('M', mktime(0, 0, 0, $this->MONTH, 1))) . substr((string) $this->YEAR, -2) . str_pad($this->autoNumber, 3, '0', STR_PAD_LEFT); // Generate a reference number based on the month and year
        $isExist = $this->philHealthServices->ifClaimNoExists($data->ID, $ref);
        if (! $isExist) {
            // if claim number does not exist, update it
            $this->philHealthServices->updateClaimNo($data->ID, $ref);
        } else {
            // try again with a different reference number
            $this->setData($data);
        }

    }
    public function export()
    {
        if (! $this->dataList) {
            session()->flash('error', 'Please click geenerate first ');
            return;
        }

        try {
            $this->dataList = $this->philHealthServices->GenerateAnnex($this->YEAR, $this->MONTH, $this->LOCATION_ID);
            $headers        =
                ['Item No',
                'Claims Ref#',
                'Patient Surname',
                'Patient Firstname',
                'Patient Middlename',
                'Member Surname',
                'Member Firstname',
                'Member Middlename',
                'Member`s PIN',
                'Member`s Category',
                'Date of Admission',
                'Date of Discharged',
                'Case Rate/ Claim Amt.',
                'ICD 10/RVS code',
                '*Claim Status']; // Could be dynamic based on UI

            $rowdata = [];
            $r       = 0;
            $TOTAL   = 0;
            foreach ($this->dataList as $list) {
                $TOTAL += $list->P1_TOTAL;
                $r++;
                $rowdata[]  = [
                    'Item No'               => $r,
                    'Claims Ref#'           => $list->CLAIM_NO,
                    'Patient Surname'       => $list->LAST_NAME,
                    'Patient Firstname'     => $list->FIRST_NAME,
                    'Patient Middlename'    => $list->MIDDLE_NAME,
                    'Member Surname'        => $list->IS_PATIENT ? $list->LAST_NAME : $list->LAST_NAME,
                    'Member Firstname'      => $list->IS_PATIENT ? $list->FIRST_NAME : $list->FIRST_NAME,
                    'Member Middlename'     => $list->IS_PATIENT ? $list->MIDDLE_NAME : $list->MIDDLE_NAME,
                    'Member`s PIN'          => $list->PIN_NO,
                    'Member`s Category'     => $list->CLASS,
                    'Date of Admission'     => date('M/d/Y', strtotime($list->DATE_ADMITTED)),
                    'Date of Discharged'    => date('M/d/Y', strtotime($list->DATE_DISCHARGED)),
                    'Case Rate/ Claim Amt.' => number_format($list->P1_TOTAL, 2),
                    'ICD 10/RVS code'       => '90935',
                    '*Claim Status'         => 'FOR FILE',
                ];
            }
// total
            $rowdata[] = [
                'Item No'               => '',
                'Claims Ref#'           => '',
                'Patient Surname'       => '',
                'Patient Firstname'     => '',
                'Patient Middlename'    => '',
                'Member Surname'        => '',
                'Member Firstname'      => '',
                'Member Middlename'     => '',
                'Member`s PIN'          => '',
                'Member`s Category'     => '',
                'Date of Admission'     => '',
                'Date of Discharged'    => 'TOTAL',
                'Case Rate/ Claim Amt.' => number_format($TOTAL, 2),
                'ICD 10/RVS code'       => '',
                '*Claim Status'         => '',
            ];
            return Excel::download(new DynamicExport($headers, $rowdata), 'AnnexExport.xlsx');
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }
    }
    public function autoSet()
    {
        $this->autoNumber == 0;
        // Reset autoNumber to 0 before generating new data

        $dataList = $this->philHealthServices->GenerateAnnex($this->YEAR, $this->MONTH, $this->LOCATION_ID);
        foreach ($dataList as $data) {
            $this->setData($data);
        }
        $this->generate();

    }

    public function render()
    {
        return view('livewire.patient-report.philhealth-annex');
    }
}

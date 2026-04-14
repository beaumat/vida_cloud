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

#[Title(('Annex D Report'))]
class PhilhealthAnnexTwo extends Component
{

    public $showAll = false;
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList     = [];
    public $yearList     = [];
    public $YEAR         = 0;
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
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->YEAR         = 0;
        $this->locationList = $this->locationServices->getList();
        $this->yearList     = $this->dateServices->YearList();
    }
    public function updatedLOCATIONID()
    {

        $this->dataList = [];
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedYEAR()
    {

        $this->dataList = [];
    }
    public function updatedMONTH()
    {

        $this->dataList = [];
    }
    public function generate()
    {
        $this->dataList = $this->philHealthServices->GenerateAnnex2($this->LOCATION_ID, $this->showAll, $this->YEAR);
    }
    public function export()
    {

        if (! $this->dataList) {
            session()->flash('error', 'Please click geenerate first ');
            return;
        }

        try {
            $this->dataList = $this->philHealthServices->GenerateAnnex2($this->LOCATION_ID, $this->showAll, $this->YEAR);
            $headers        = ['Item No',
                'Yr Start From',
                'Claims Series Reference',
                'Patient Surname',
                'Patient Firstname',
                'Patient Middlename',
                'Member Surname',
                'Member Firstname',
                'Member Middlename', 'Member`s PIN',
                'Date of Admission',
                'Date of Discharged',
                'Date of Filed',
                'Date of Refiled',
                'ICD 10/RVS code',
                'Case Rate/ Claim Amt.',
                '*Claim Status']; // Could be dynamic based on UI

            $rowdata = [];
            $r       = 0;
            $TOTAL   = 0;
            foreach ($this->dataList as $list) {
                $TOTAL += $list->P1_TOTAL;
                $r++;
                $rowdata[]  = [
                    'Item No'                 => $r,
                    'Yr Start From'           => $list->YEAR,
                    'Claims Series Reference' => $list->AR_NO,
                    'Patient Surname'         => $list->LAST_NAME,
                    'Patient Firstname'       => $list->FIRST_NAME,
                    'Patient Middlename'      => $list->MIDDLE_NAME,
                    'Member Surname'          => $list->IS_PATIENT ? $list->LAST_NAME : $list->LAST_NAME,
                    'Member Firstname'        => $list->IS_PATIENT ? $list->FIRST_NAME : $list->FIRST_NAME,
                    'Member Middlename'       => $list->IS_PATIENT ? $list->MIDDLE_NAME : $list->MIDDLE_NAME,
                    'Member`s PIN'            => $list->PIN_NO,
                    'Date of Admission'       => date('M/d/Y', strtotime($list->DATE_ADMITTED)),
                    'Date of Discharged'      => date('M/d/Y', strtotime($list->DATE_DISCHARGED)),
                    'Date of Filed'           => date('M/d/Y', strtotime($list->AR_DATE)),
                    'Date of Refiled'         => 'N / A ',
                    'ICD 10/RVS code'         => '90935',
                    'Case Rate/ Claim Amt.'   => number_format($list->P1_TOTAL, 2),
                    '*Claim Status'           => $list->PAYMENT_AMOUNT > 0 ? 'Paid' : 'In-Progress',
                ];
            }

            $rowdata[] = [
                'Item No'                 => '',
                'Yr Start From'           => '',
                'Claims Series Reference' => '',
                'Patient Surname'         => '',
                'Patient Firstname'       => '',
                'Patient Middlename'      => '',
                'Member Surname'          => '',
                'Member Firstname'        => '',
                'Member Middlename'       => '',
                'Member`s PIN'            => '',
                'Date of Admission'       => '',
                'Date of Discharged'      => '',
                'Date of Filed'           => '',
                'Date of Refiled'         => '',
                'ICD 10/RVS code'         => 'TOTAL',
                'Case Rate/ Claim Amt.'   => number_format($TOTAL, 2),
                '*Claim Status'           => '',
            ];

            return Excel::download(new DynamicExport($headers, $rowdata), 'AnnexBExport.xlsx');

        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Error generating Excel: ' . $e->getMessage());
        }

    }
    public function render()
    {
        return view('livewire.patient-report.philhealth-annex-two');
    }
}

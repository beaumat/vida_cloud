<?php
namespace App\Livewire\PatientReport;

use App\Exports\PatientReport\PhilhealthAvailmentExport;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Philhealth Availment List')]
class PhilHealthAvailmentList extends Component
{
    public $search;
    public $LOCATION_ID;
    public $locationList = [];
    public $YEAR;
    public $yearList       = [];
    public $patientList    = [];
    public bool $SelectAll = false;
    public string $ids;
    public $selectPatient = [];
    private $contactServices;
    private $locationServices;
    private $dateServices;
    private $userServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices,
        DateServices $dateServices,
        UserServices $userServices
    ) {
        $this->contactServices  = $contactServices;
        $this->locationServices = $locationServices;
        $this->dateServices     = $dateServices;
        $this->userServices     = $userServices;
    }
    public function mount()
    {
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->YEAR         = $this->dateServices->NowYear();
        $this->locationList = $this->locationServices->getList();
        $this->yearList     = $this->dateServices->YearList();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->patientList as $list) {
                $this->selectPatient[$list->ID] = true;
            }
        } else {
            $this->ResetData();
        }
    }
    private function ResetData()
    {
        $this->SelectAll = false;
        $this->reset('selectPatient');
    }
    public function exportAll()
    {

        $header[] = [
            'NAME'                  => 'PATIENT NAME',
            'TOTAL_DIALYZER'        => 'TOTAL DIALYZER',
            'JAN'                   => 'JAN',
            'FEB'                   => 'FEB',
            'MAR'                   => 'MAR',
            'APR'                   => 'APR',
            'MAY'                   => 'MAY',
            'JUN'                   => 'JUN',
            'JUL'                   => 'JUL',
            'AUG'                   => 'AUG',
            'SEP'                   => 'SEP',
            'OCT'                   => 'OCT',
            'NOV'                   => 'NOV',
            'DEC'                   => 'DEC',
            'NO_ACTUAL_CONFINEMENT' => 'NO. ACTUAL CONFINEMENT',
            'NO_OTHER_CONFINEMENT'  => 'NO. OTHER CONFINEMENT',
            'TOTAL_CONFINEMENT'     => 'TOTAL CONFINEMENT',
        ];

        $rowdata = [];

        $myData = $this->contactServices->getPatientAvailmentList($this->search, $this->LOCATION_ID, $this->YEAR);
        foreach ($myData as $item) {
            $rowdata[] = [
                'NAME'                  => $item->NAME,
                'TOTAL_DIALYZER'        => $item->TOTAL_ITEMS + $item->TOTAL_OTHER_ITEM,
                'JAN'                   => $item->TOTAL_JAN,
                'FEB'                   => $item->TOTAL_FEB,
                'MAR'                   => $item->TOTAL_MAR,
                'APR'                   => $item->TOTAL_APR,
                'MAY'                   => $item->TOTAL_MAY,
                'JUN'                   => $item->TOTAL_JUN,
                'JUL'                   => $item->TOTAL_JUL,
                'AUG'                   => $item->TOTAL_AUG,
                'SEP'                   => $item->TOTAL_SEP,
                'OCT'                   => $item->TOTAL_OCT,
                'NOV'                   => $item->TOTAL_NOV,
                'DEC'                   => $item->TOTAL_DEC,
                'NO_ACTUAL_CONFINEMENT' => $item->TOTAL_DAYS,
                'NO_OTHER_CONFINEMENT'  => $item->TOTAL_OTHER,
                'TOTAL_CONFINEMENT'     => $item->TOTAL_OTHER + $item->TOTAL_DAYS,
            ];
        }

        return Excel::download(new PhilhealthAvailmentExport($rowdata, $header), $this->YEAR . 'PhilhealthAvailmentListExport.xlsx');
    }
    public function printAll()
    {
        $this->ids = "";
        foreach ($this->selectPatient as $pid => $isSelect) {
            if ($isSelect) {
                if ($this->ids == "") {
                    $this->ids = $pid;
                } else {
                    $this->ids = $this->ids . "," . $pid;
                }
            }
        }

        if ($this->ids == "") {
            return;
        }

        $url = route('reportsphilhealth_availment_list_print', ['id' => $this->ids, 'locationid' => $this->LOCATION_ID, 'year' => $this->YEAR]);
        $this->dispatch('OpenNewTab', data: $url);
    }
    public function updatedLocationId()
    {
        $this->ResetData();

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function updatedYear()
    {
        $this->ResetData();
    }

    public function render()
    {

        $this->patientList = $this->contactServices->getPatientAvailmentList($this->search, $this->LOCATION_ID, $this->YEAR);

        return view('livewire.patient-report.phil-health-availment-list');
    }
}

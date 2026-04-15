<?php
namespace App\Livewire\PatientReport;

use App\Exports\TreatmentReportExport;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Patient Treatment Report')]
class PatientTreatmentReport extends Component
{

    public int $YEAR;
    public int $MONTH;
    public $monthList = [];
    public int $LOCATION_ID;
    public $locationList    = [];
    public $dataList        = [];
    public array $dailyList = [];
    public $startDate;
    public $endDate;
    public $patientSelected = [];
    private $patientReportServices;
    private $dateServices;
    public int $count = 0;
    public int $index = 0;
    public int $row   = 0;
    public int $total;
    public int $sum = 0;

    public int $patient  = 0;
    public $storeTotal   = [];
    public $phicTotal    = [];
    public $premTotal    = [];
    public $regularTotal = [];

    private $locationServices;
    private $userServices;
    public function boot(PatientReportServices $patientReportServices, DateServices $dateServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->patientReportServices = $patientReportServices;
        $this->dateServices          = $dateServices;
        $this->locationServices      = $locationServices;
        $this->userServices          = $userServices;
    }
    public function reload()
    {
        $this->YEAR  = $this->dateServices->NowYear();
        $this->MONTH = $this->dateServices->NowMonth();
        $this->DaySetup();
        $this->dataList = [];
    }
    public function DaySetup()
    {
        $this->dailyList    = [];
        $this->storeTotal   = [];
        $this->phicTotal    = [];
        $this->premTotal    = [];
        $this->regularTotal = [];

        $this->startDate = Carbon::create($this->YEAR, $this->MONTH, 1); // August 1st of the current year
        $this->endDate   = $this->startDate->copy()->endOfMonth();       // End of August

        // Loop through each day in August
        for ($date = $this->startDate; $date->lte($this->endDate); $date->addDay()) {
            $this->dailyList[]    = $date->format('Y-m-d'); // Format the date as 'Y-m-d'
            $this->storeTotal[]   = 0;
            $this->phicTotal[]    = 0;
            $this->premTotal[]    = 0;
            $this->regularTotal[] = 0;
        }
    }
    public function generate()
    {

        $this->DaySetup();
        $this->dataList = $this->patientReportServices->getMonthlyTreatment(
            $this->YEAR,
            $this->MONTH,
            $this->dailyList,
            $this->patientSelected,
            $this->LOCATION_ID
        );
    }
    public function ExportGenerate()
    {

        return Excel::download(new TreatmentReportExport(
            $this->patientReportServices,
            $this->YEAR,
            $this->MONTH,
            $this->patientSelected,
            $this->LOCATION_ID
        ), 'treatment-report.xlsx');

    }
    public function mount()
    {
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->monthList    = $this->dateServices->MonthList();
        $this->reload();
    }
    public function render()
    {
        return view('livewire.patient-report.patient-treatment-report');
    }
}

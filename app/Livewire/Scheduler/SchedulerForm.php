<?php
namespace App\Livewire\Scheduler;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\HemodialysisMachineServices;
use App\Services\LocationServices;
use App\Services\ScheduleServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Schedules')]
class SchedulerForm extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month;
    public $year;
    public $contactList      = [];
    public $refreshComponent = false;
    protected $listeners     = ['reloadComponent'];
    public $CONTACT_ID;
    public $LOCATION_ID;
    public $HEMO_MACHINE_ID;
    public $locationList = [];
    private $locationServices;
    private $contactServices;
    private $userServices;
    private $dateServices;
    private $scheduleServices;
    public $monthList            = [];
    public $scheduleStatusList   = [];
    public int $scheduleStatusId = 0;
    private $hemodialysisMachineServices;
    public function boot(
        LocationServices $locationServices,
        ContactServices $contactServices,
        UserServices $userServices,
        DateServices $dateServices,
        ScheduleServices $scheduleServices,
        HemodialysisMachineServices $hemodialysisMachineServices
    ) {
        $this->locationServices            = $locationServices;
        $this->contactServices             = $contactServices;
        $this->userServices                = $userServices;
        $this->dateServices                = $dateServices;
        $this->scheduleServices            = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
    }

    public function updatedcontactid()
    {
        $this->reloadComponent();
        $data = $this->contactServices->get($this->CONTACT_ID, 3);
        if ($data) {
            $this->HEMO_MACHINE_ID = $this->hemodialysisMachineServices->getDefaultByLocation($data->LOCATION_ID); //$data->PATIENT_TYPE_ID;
            return;
        }
        $this->HEMO_MACHINE_ID = 0;
    }
    public function updatedlocationid()
    {
        $this->reloadComponent();
    }
    public function reloadComponent()
    {
        $this->refreshComponent = ! $this->refreshComponent;
    }
    public function updatedyear()
    {
        if ($this->year < 2020) {
            $this->year = 2020;
        }
        $this->reloadComponent();
    }
    public function updatedmonth()
    {
        $this->reloadComponent();
    }
    private function resetDate()
    {
        $this->month = $this->dateServices->NowMonth();
        $this->year  = $this->dateServices->NowYear();
    }
    public function mount()
    {
        $this->scheduleStatusList = $this->scheduleServices->ScheduleStatusList();
        $this->LOCATION_ID        = $this->userServices->getLocationDefault();
        $this->monthList          = $this->dateServices->MonthList();
        $this->resetDate();
    }
    public function todayMonth()
    {
        $this->resetDate();
        $this->reloadComponent();
    }
    public function nextMonth()
    {
        $this->month = $this->month == 12 ? 1 : $this->month + 1;
        $this->year  = $this->month == 1 ? $this->year + 1 : $this->year;

        $this->reloadComponent();
    }
    public function previousMonth()
    {
        $this->month = $this->month == 1 ? 12 : $this->month - 1;
        $this->year  = $this->month == 12 ? $this->year - 1 : $this->year;

        $this->reloadComponent();
    }
    public function openMonitor(string $SHIFT_ID, string $DATE)
    {
        $this->dispatch('open-shift-monitoring', reglist: ['SHIFT_ID' => $SHIFT_ID, 'CONTACT_ID' => $this->CONTACT_ID, 'LOCATION_ID' => $this->LOCATION_ID, 'DATE' => $DATE]);
    }
    #[On('load-schedule-by-contact')]
    public function render()
    {

        $this->contactList  = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->locationList = $this->locationServices->getList();
        $scheduleList       = $this->scheduleServices->ContactSchedule($this->CONTACT_ID ?? 0, $this->LOCATION_ID ?? 0, $this->scheduleStatusId, 10);

        return view('livewire.scheduler.scheduler-form', ['scheduleList' => $scheduleList]);
    }
}

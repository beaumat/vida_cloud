<?php
namespace App\Livewire\Scheduler;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\ScheduleServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Schedules')]
class SchedulerList extends Component
{
    public $month;
    public $year;
    public $schedContact     = [];
    public $contactList      = [];
    public $refreshComponent = false;
    protected $listeners     = ['reloadComponent'];
    public $LOCATION_ID;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $scheduleServices;
    public $monthList    = [];
    public $scheduleList = [];

    public $DATE;

    public function boot(LocationServices $locationServices, UserServices $userServices, DateServices $dateServices, ScheduleServices $scheduleServices)
    {
        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
        $this->dateServices     = $dateServices;
        $this->scheduleServices = $scheduleServices;
    }

    #[On('make-reload')]
    public function loadScheduleByContact()
    {

    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

        $this->reloadComponent();
        $this->dispatch('make-reload');

    }
    #[On('back-load')]
    public function DateSet($Date)
    {
        $this->DATE = $Date;
        $this->reloadContactList($Date);
    }
    public function reloadContactList($Date)
    {
        try {

            $date               = Carbon::createFromFormat('Y-m-d', $Date);
            $this->schedContact = $this->scheduleServices->scheduleList($date, $this->LOCATION_ID);
            $this->DATE         = $date;
            $this->reloadComponent();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function reloadComponent()
    {
        try {
            $this->refreshComponent = ! $this->refreshComponent;
        } catch (\Throwable $th) {
            //throw $th;
        }
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
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
        $this->monthList    = $this->dateServices->MonthList();
        $this->todayMonth();
       
    }

    public function todayMonth()
    {
        $this->year  = Carbon::now()->year;
        $this->month = Carbon::now()->month;
        $this->reloadComponent();
        $this->dispatch('back-load', Date: Carbon::now()->format('Y-m-d'));
    }
    public function nextMonth()
    {
        $this->DATE  = null;
        $this->month = $this->month == 12 ? 1 : $this->month + 1;
        $this->year  = $this->month == 1 ? $this->year + 1 : $this->year;
        $this->reloadComponent();
    }
    public function previousMonth()
    {
        $this->DATE  = null;
        $this->month = $this->month == 1 ? 12 : $this->month - 1;
        $this->year  = $this->month == 12 ? $this->year - 1 : $this->year;
        $this->reloadComponent();
    }

    public function openModalPrint()
    {
        $this->dispatch('print-modal');
    }
    public function render()
    {
        return view('livewire.scheduler.scheduler-list');
    }
}

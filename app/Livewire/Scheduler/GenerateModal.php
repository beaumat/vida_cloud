<?php

namespace App\Livewire\Scheduler;

use App\Models\Contacts;
use App\Models\Shift;
use App\Services\DateServices;
use App\Services\ScheduleServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class GenerateModal extends Component
{

    #[Reactive]
    public int $LOCATION_ID, $MONTH, $YEAR;
    public bool $showModal;
    public $contactList = [];
    public $patientSelected = [];
    public int $WEEKLY_ID = 1;
    public bool $SelectAll = false;
    public $weekLevel = [];
    private $dateServices;
    private $scheduleServices;
    public $weekdays = [];
    public string $tab = "active";
    public $shiftList = [];
    public function SelectTab(string $tb)
    {
        $this->tab = $tb;
    }
    public function boot(

        DateServices $dateServices,
        ScheduleServices $scheduleServices
    ) {

        $this->dateServices = $dateServices;
        $this->scheduleServices = $scheduleServices;
    }
    public function mount($LOCATION_ID, $MONTH, $YEAR)
    {
        $this->MONTH = $MONTH;
        $this->YEAR = $YEAR;

        $this->LOCATION_ID = $LOCATION_ID;
        $this->weekLevel = $this->dateServices->WeeklyLevel();

        $this->reloadShift();
        $this->reloadWeekly();
    }
    private function reloadShift()
    {
        $this->shiftList = Shift::orderBy('LINE_NO')->get();
    }
    private function reloadWeekly()
    {

        $this->weekdays = $this->dateServices->Get7Days($this->YEAR, $this->MONTH, $this->WEEKLY_ID);
    }
    public function updatedyear()
    {
        $this->reloadWeekly();
    }
    public function updatedmonth()
    {
        $this->reloadWeekly();
    }
    public function updatedweeklyid()
    {
        $this->SelectAll = false;
        $this->reset('patientSelected');
        $this->reloadWeekly();
    }
    public function updatedSelectAll($value)
    {
       
        if ($value) {
            foreach ($this->contactList as $list) {
                $this->patientSelected[$list->ID] = true;
            }
        } else {
           
            $this->reset('patientSelected');
        }
    }

    public function generate()
    {
        $gotSelected = false;
        foreach ($this->patientSelected as $patientID => $isSelected) {
            if ($isSelected) {
                $gotSelected = true;
                $this->scheduleServices->AutoGenerateSchedule($patientID, $this->LOCATION_ID, $this->YEAR, $this->MONTH, $this->weekdays, $this->shiftList);
            }
        }
        if ($gotSelected) {
            $this->dispatch('back-load', Date: Carbon::now()->format('Y-m-d'));
            $this->showModal = false;
        }
    }

    public function openModal()
    {
        foreach ($this->contactList as $list) {
            $this->patientSelected[$list->ID] = false;
        }

        $this->showModal = true;
    }
    #[On('schedule-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function LoadContact()
    {
        $this->contactList = [];
        if (!$this->weekdays) {
            return;
        }

        $this->contactList = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME',
                'hm.DESCRIPTION as PATIENT_TYPE',
                'ps.DESCRIPTION as PATIENT_STATUS',
                'contact.ADMITTED',
                'contact.LONG_HRS_DURATION',
                'st.DESCRIPTION as SCHEDULE_TYPE',
                'contact.SCHEDULE_TYPE as SCHEDULE_TYPE_ID',
                'contact.PATIENT_TYPE_ID',
                'contact.PATIENT_STATUS_ID',
                'contact.DATE_ADMISSION'
            ])
            ->join('schedule_type as st', 'st.ID', '=', 'contact.SCHEDULE_TYPE')
            ->join('hemodialysis_machine as hm', 'hm.ID', '=', 'contact.PATIENT_TYPE_ID')
            ->join('patient_status as ps', 'ps.ID', '=', 'contact.PATIENT_STATUS_ID')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('schedules as s')
                    ->whereRaw('s.CONTACT_ID = contact.ID')
                    ->whereRaw('s.HEMO_MACHINE_ID = hm.ID')
                    ->whereRaw('s.LOCATION_ID = contact.LOCATION_ID')
                    ->whereBetween('s.SCHED_DATE', [$this->weekdays[0], $this->weekdays[count($this->weekdays) - 1]]);
            })
            ->where('contact.TYPE', 3)
            ->where('contact.INACTIVE', 0)
            ->whereNull('contact.HIRE_DATE')
            ->where('contact.LOCATION_ID', $this->LOCATION_ID)
            ->orderBy('contact.DATE_ADMISSION', 'asc')
            ->orderBy('contact.ID', 'asc')
            ->get();
    }
    public function render()
    {
        $this->LoadContact();
        return view('livewire.scheduler.generate-modal');
    }
}

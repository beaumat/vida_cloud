<?php

namespace App\Livewire\Scheduler;

use App\Services\DateServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintSchedules extends Component
{
    #[Reactive]
    public int $YEAR;
    #[Reactive]
    public int $MONTH;
    #[Reactive]
    public int $LOCATION_ID;
    public bool $showModal = false;
    public $shiftList = [];
    public string $DATE_START;
    public string $DATE_END;
    public int $SHIFT_ID = 0;
    public int $WEEKLY_ID;
    public $weekdays = [];
    public $weekLevels = [];
    private $shiftServices;
    private $dateServices;
    private $scheduleServices;
    public function boot(ShiftServices $shiftServices, DateServices $dateServices, ScheduleServices $scheduleServices)
    {
        $this->shiftServices = $shiftServices;
        $this->dateServices = $dateServices;
        $this->scheduleServices = $scheduleServices;
    }
    public function mount()
    {
        $this->WEEKLY_ID = 1;
    }
    #[On('reload-list-schedule')]
    public function updatedshowModal()
    {
        $this->weekLevels = [];
        if ($this->showModal == true) {

            $this->shiftList = $this->shiftServices->List();
            $dataWeek =  $this->dateServices->WeeklyLevel();

            foreach ($dataWeek as $list) {
                $data = $this->dateServices->Get7Days($this->YEAR, $this->MONTH, $list['ID']);

                $dateFirst = "";
                $dateLast = "";

                foreach ($data as $day) {
                    if ($dateFirst == "") {
                        $dateFirst = $day;
                    }
                    $dateLast = $day;
                }
                $total = $this->scheduleServices->getCountScheduleList($dateFirst, $dateLast, $this->LOCATION_ID);
                $this->weekLevels[] = ['ID' => $list['ID'], 'DESCRIPTION' => $list['DESCRIPTION'], 'TOTAL' => $total];
            }
        }
    }


    #[On('print-modal')]
    public function openModal()
    {

        $this->showModal = true;
        $this->dispatch('reload-list-schedule');
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
    public function render()
    {
        return view('livewire.scheduler.print-schedules');
    }
}

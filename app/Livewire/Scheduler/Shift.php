<?php

namespace App\Livewire\Scheduler;

use App\Services\ContactServices;
use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Shift extends Component
{
    public $loadingId = null;
    public $ID;
    public int $CONTACT_ID;
    public $shiftList = [];
    public int $SHIFT_ID = 0;
    private $scheduleServices;
    private $hemodialysisMachineServices;
    private $contactServices;
    public int $LOCATION_ID;
    public int $HEMO_MACHINE_ID;
    public int $EXIST_HEMO;
    public int $STATUS_ID;
    #[Reactive]
    public $hemoMachineList = [];

    public function boot(
        ScheduleServices $scheduleServices,
        HemodialysisMachineServices $hemodialysisMachineServices,
        ContactServices $contactServices
    ) {
        $this->scheduleServices = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->contactServices = $contactServices;
    }
    public function mount($id, $contact_id, $shiftList, $location_id, $hemo_machine_id)
    {
        $this->ID = $id; // is date
        $this->CONTACT_ID = $contact_id;
        $this->$shiftList = $shiftList;
        $this->LOCATION_ID = $location_id;
        $data = $this->scheduleServices->getSchedHemo($this->CONTACT_ID, $id, $this->LOCATION_ID);

        if ($data) {
            $this->SHIFT_ID = $data->SHIFT_ID ?? 0;
            $this->STATUS_ID = $data->SCHED_STATUS ?? 0;
            $this->HEMO_MACHINE_ID = $data->HEMO_MACHINE_ID ?? 0;
            $this->EXIST_HEMO = $data->EXIST_HEMO ?? 0;
        } else {
            $this->EXIST_HEMO = 0;
            $this->HEMO_MACHINE_ID = $hemo_machine_id;
        }
    }
    public function CheckingIsMaximumCapacity(string $DATE, int $CONTACT_ID, int $LOCATION_ID, int $SHIFT_ID, int $HEMO_M_ID): bool
    {
        $count = (int) $this->scheduleServices->CheckingType($SHIFT_ID, $CONTACT_ID, $DATE, $LOCATION_ID, $HEMO_M_ID);

        $capacity = (int) $this->hemodialysisMachineServices->GetCapacity($HEMO_M_ID);

        if ($count < $capacity) {
            return false;
        } else {
            return true;
        }
    }
    public function openList()
    {
        $this->dispatch('open-shift-monitoring', reglist: ['SHIFT_ID' => $this->SHIFT_ID, 'CONTACT_ID' => $this->CONTACT_ID, 'LOCATION_ID' => $this->LOCATION_ID, 'DATE' => $this->ID]);
    }
    public function UpdatedHemoMachineId()
    {
        $scheduleData = $this->scheduleServices->get($this->CONTACT_ID, $this->ID, $this->LOCATION_ID);

        if ($scheduleData) {
            $this->scheduleServices->UpdateHemoMachine($scheduleData->CONTACT_ID, $this->ID, $scheduleData->LOCATION_ID, $this->HEMO_MACHINE_ID);
            $this->contactServices->UpdatePatientType($this->CONTACT_ID, $this->HEMO_MACHINE_ID);
            $this->dispatch('load-schedule-by-contact');
        }
    }
    public function save(int $shift_id)
    {
        $this->loadingId = $shift_id;
        if ($this->CONTACT_ID > 0) {
            try {
                $scheduleData = $this->scheduleServices->get($this->CONTACT_ID, $this->ID, $this->LOCATION_ID);

                if ($scheduleData) {

                    if ($shift_id == 0) {
                        $this->scheduleServices->Delete($scheduleData->ID, $this->LOCATION_ID);
                    } else {
                        // $isMaximum = $this->CheckingIsMaximumCapacity($this->ID, $this->CONTACT_ID, $this->LOCATION_ID, $shift_id, $this->HEMO_MACHINE_ID);
                        $this->scheduleServices->Update($this->CONTACT_ID, $this->ID, $shift_id, $scheduleData->SCHED_STATUS, $scheduleData->STATUS_LOG, $this->LOCATION_ID, $this->HEMO_MACHINE_ID);
                    }
                } elseif ($shift_id != 0) {

                    // $isMaximum = $this->CheckingIsMaximumCapacity($this->ID, $this->CONTACT_ID, $this->LOCATION_ID, $shift_id, $this->HEMO_MACHINE_ID);
                    $this->scheduleServices->Store($shift_id, $this->CONTACT_ID, $this->ID, 0, null, $this->LOCATION_ID, $this->HEMO_MACHINE_ID);
                }
                $this->dispatch('load-schedule-by-contact');
               
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.scheduler.shift');
    }
}

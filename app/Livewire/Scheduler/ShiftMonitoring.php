<?php

namespace App\Livewire\Scheduler;

use App\Services\ColorServices;
use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftMonitoring extends Component
{

    public $i = 1;
    public $dataList = [];
    private $scheduleServices;
    private $hemodialysisMachineServices;
    private int $totalCapacity = 0;

    public $SHIFT_NAME;
    private $shiftServices;
    private $colorServices;
    public function boot(ScheduleServices $scheduleServices, HemodialysisMachineServices $hemodialysisMachineServices, ShiftServices $shiftServices, ColorServices $colorServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->shiftServices = $shiftServices;
        $this->colorServices = $colorServices;
    }

    public $showModal;
    public int $SHIFT_ID;
    public int $CONTACT_ID;
    public int $LOCATION_ID;
    public string $DATE;
    private int $prev_capacity = 0;
    private int $total_set = 0;
    public int $color_batch_id  = 0;
    private function LoadData()
    {
        $n = 0;
        $this->prev_capacity = 0;
        $this->totalCapacity = 0;
        $type = $this->hemodialysisMachineServices->GetList($this->LOCATION_ID);
        $this->color_batch_id  = 0;
        foreach ($type as $item) { // type machine
            $noCapacity = (int) $item->CAPACITY;
            $this->color_batch_id  = $this->color_batch_id  + 1;
            $extra_class = (string) $this->colorServices->getColorClass($this->color_batch_id);
            $this->totalCapacity = $this->totalCapacity + $noCapacity; // total capacity

            $data = $this->scheduleServices->scheduleListByShift($this->DATE, $this->LOCATION_ID, $this->SHIFT_ID, $item->ID);  // the  list of reg schedule
            if ($data->count() >  $noCapacity  && $this->prev_capacity  > $this->total_set) {
                $adjust = $data->count() - $noCapacity;
                $n = $n - $adjust;
            }

            foreach ($data as $dataList) {
                $this->dataList[$n] = ['ID' => $n + 1, 'NAME' => $dataList->CONTACT_NAME, 'TYPE' => $item->ID, 'CONTACT_ID' => $dataList->CONTACT_ID, 'EXTRA_CLASS' => $extra_class];
                $this->total_set = $this->total_set + 1;
                $n++;
            }

            if ($this->totalCapacity > $n) {
                for ($r = $n; $r < $this->totalCapacity; $r++) {
                    $this->dataList[$n] = ['ID' => $n + 1, 'NAME' => '', 'TYPE' => $item->ID, 'CONTACT_ID' => '0', 'EXTRA_CLASS' => $extra_class];
                    $n++;
                }
            }

            $this->prev_capacity =  $noCapacity;
        }


        $data = $this->shiftServices->get($this->SHIFT_ID);

        if ($data) {
            $this->SHIFT_NAME = $data->NAME;
        } else {
            $this->SHIFT_NAME = '';
        }
    }


    #[On('open-shift-monitoring')]
    public function getList($reglist)
    {
        $this->SHIFT_ID = $reglist['SHIFT_ID'];
        $this->LOCATION_ID = $reglist['LOCATION_ID'];
        $this->CONTACT_ID = $reglist['CONTACT_ID'];
        $this->DATE = $reglist['DATE'];
        $this->showModal = true;
        $this->LoadData();
    }


    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {

        return view('livewire.scheduler.shift-monitoring');
    }
}

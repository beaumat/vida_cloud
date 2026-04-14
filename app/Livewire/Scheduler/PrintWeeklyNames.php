<?php

namespace App\Livewire\Scheduler;

use App\Services\ColorServices;
use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use Livewire\Component;

class PrintWeeklyNames extends Component
{
    public $i = 1;
    public $dataList = [];
    private $scheduleServices;
    private $hemodialysisMachineServices;
    private int $totalCapacity = 0;
    private $colorServices;
    public function boot(ScheduleServices $scheduleServices, HemodialysisMachineServices $hemodialysisMachineServices, ColorServices $colorServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->colorServices = $colorServices;
    }
    private int $prev_capacity = 0;
    private int $color_batch_id = 0;
    private int $total_set = 0;
    public function mount($date, $shift, $location)
    {
        $n = 0;
        $this->prev_capacity = 0;
        $this->totalCapacity = 0;
        $type = $this->hemodialysisMachineServices->GetList($location);
        $this->color_batch_id  = 0;
    
        foreach ($type as $item) { // type machine

            $noCapacity = (int) $item->CAPACITY;
            $this->color_batch_id  = $this->color_batch_id  + 1;
            $extra_class = (string) $this->colorServices->getPrintClass($this->color_batch_id);
            $this->totalCapacity = $this->totalCapacity + $noCapacity; // total capacity

            $data = $this->scheduleServices->scheduleListByShift($date, $location, $shift, $item->ID);  // the  list of reg schedule

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
    }
    public function render()
    {
        return view('livewire.scheduler.print-weekly-names');
    }
}

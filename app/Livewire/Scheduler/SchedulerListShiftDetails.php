<?php
namespace App\Livewire\Scheduler;

use App\Services\ColorServices;
use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SchedulerListShiftDetails extends Component
{

    public int $SHIFT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE;

    public $dataList = [];
    private $scheduleServices;
    private $hemodialysisMachineServices;

    private int $totalCapacity = 0;

    private $colorServices;

    private int $prev_capacity = 0;
    private int $total_set = 0;
    public int $color_batch_id = 0;

    public function boot(ScheduleServices $scheduleServices, HemodialysisMachineServices $hemodialysisMachineServices, ColorServices $colorServices)
    {
        $this->scheduleServices            = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->colorServices               = $colorServices;
    }

    private function LoadData()
    {
        $this->dataList       = [];
        $n                    = 0;
        $this->prev_capacity  = 0;
        $this->totalCapacity  = 0;
        $type                 = $this->hemodialysisMachineServices->GetList($this->LOCATION_ID);
        $this->color_batch_id = 0;

        foreach ($type as $item) { // type machine
            $noCapacity           = (int) $item->CAPACITY;
            $this->color_batch_id = $this->color_batch_id + 1;
            $extra_class          = (string) $this->colorServices->getColorClass($this->color_batch_id);
            $this->totalCapacity  = $this->totalCapacity + $noCapacity;                                                                        // total capacity
            $data                 = $this->scheduleServices->scheduleListByShift($this->DATE, $this->LOCATION_ID, $this->SHIFT_ID, $item->ID); // the  list of reg schedule

            if ($data->count() > $noCapacity && $this->prev_capacity > $this->total_set) {
                $adjust = $data->count() - $noCapacity;
                $n      = $n - $adjust;
            }

            foreach ($data as $dataList) {
                $this->dataList[$n] = ['ID' => $n + 1, 'NAME' => $dataList->CONTACT_NAME, 'TYPE' => $item->ID, 'CONTACT_ID' => $dataList->CONTACT_ID, 'EXTRA_CLASS' => $extra_class, 'STATUS' => $dataList->STATUS];
                $this->total_set    = $this->total_set + 1;
                $n++;
            }

            if ($this->totalCapacity > $n) {
                for ($r = $n; $r < $this->totalCapacity; $r++) {
                    $this->dataList[$n] = ['ID' => $n + 1, 'NAME' => '', 'TYPE' => $item->ID, 'CONTACT_ID' => '0', 'EXTRA_CLASS' => $extra_class, 'STATUS' => ''];
                    $n++;
                }
            }

            $this->prev_capacity = $noCapacity;
        }
    }

    public function render()
    {
        $this->LoadData();

        return view('livewire.scheduler.scheduler-list-shift-details');
    }
}

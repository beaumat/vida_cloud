<?php

namespace App\Livewire\PhilHealth;

use App\Services\ItemSoaServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintItemized3 extends Component
{
    #[Reactive]
    public bool $OUTPUT_SIGN;
    #[Reactive]
    public $breakDownDate = [];
    public $date;
    public $dataList = [];
    public int $qty = 1;
    public int $LOCATION_ID = 0;
    public int $PATIENT_ID = 0;

    private $itemSoaServices;

    public function boot(ItemSoaServices $itemSoaServices)
    {
        $this->itemSoaServices = $itemSoaServices;
    }
    public $dateList = [];
    public function mount( $num = null, int $locationid, $date = null,  $patientId = null)
    {
        $this->LOCATION_ID = $locationid;
        $this->PATIENT_ID = $patientId ?? 0;

        foreach ($this->breakDownDate as $list) {
            $this->dateList[] = $list->DATE;
        }

        $this->date = $date;
        $this->qty = $num ?? 0;
        $this->dataList = $this->itemSoaServices->GetList($locationid);
    }
    public function render()
    {
        return view('livewire.phil-health.print-itemized3');
    }
}

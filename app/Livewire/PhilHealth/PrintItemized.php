<?php

namespace App\Livewire\PhilHealth;

use App\Services\ItemSoaServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintItemized extends Component
{
    #[Reactive]
    public $breakDownDate = [];
    public string $date;
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
    public function mount(int $num, int $locationid, string $date , int $patientId = 0)
    {
        $this->LOCATION_ID = $locationid;
        $this->PATIENT_ID = $patientId;
  
        foreach($this->breakDownDate as $list) {
            $this->dateList[] = $list->DATE;
        }    
        
        $this->date = $date;
        $this->qty = $num;
        $this->dataList = $this->itemSoaServices->GetList($locationid);
    }
    public function render()
    {
        return view('livewire.phil-health.print-itemized');
    }
}

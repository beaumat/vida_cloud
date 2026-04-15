<?php

namespace App\Livewire\Patient;

use App\Services\DateServices;
use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class PhilhealthRecord extends Component
{

  
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;

    public $search = "";
    public int $YEAR;
    private $philHealthServices;
    private $dateServices;
    public function boot(PhilHealthServices $philHealthServices, DateServices $dateServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->dateServices = $dateServices;
    }
    public function mount() {
        $this->YEAR = $this->dateServices->NowYear();
    }
    public function AddTemp()
    {
        $this->philHealthServices->PreSaveTemp($this->CONTACT_ID, $this->LOCATION_ID);
        // $this->philHealthServices->PrintPreSign();
    }
    public function PrintPreSign()
    {
        $this->philHealthServices->PrintEmpty($this->CONTACT_ID);
    }
    public function delete($id)
    {
        $this->philHealthServices->Delete($id);
    }
    public function render()
    {
        $dataList = $this->philHealthServices->PatientRecord($this->search, $this->CONTACT_ID, 15, $this->LOCK_LOCATION_ID);

        return view('livewire.patient.philhealth-record', ['dataList' => $dataList]);
    }
}

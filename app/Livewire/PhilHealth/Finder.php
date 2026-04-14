<?php

namespace App\Livewire\PhilHealth;

use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Finder extends Component
{
    public $search = '';
    public int $perPage = 20;
    public int $locationid;
    public $locationList = [];
    public $ADMITTED;
    public $DISCHARGED;
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    public bool $showModal = false;
    public $dataList = [];
    public function boot(
        PhilHealthServices $philHealthServices,
        LocationServices $locationServices,
        UserServices $userServices,

    ) {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;

    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    #[On('open-finder')]
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->philHealthServices->SearchFinder($this->search, $this->locationid);
        } else {
            $this->dataList = [];
        }

        return view('livewire.phil-health.finder');
    }
}

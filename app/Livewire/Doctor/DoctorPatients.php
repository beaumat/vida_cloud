<?php

namespace App\Livewire\Doctor;

use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Livewire\Component;

class DoctorPatients extends Component
{   
    public int $doctorId;
    public $dataList = [];
    private $philHealthProfFeeServices;
    public function boot(PhilHealthProfFeeServices $philHealthProfFeeServices)
    {
        // Initialize the PhilHealthServices instance
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;

    }
    public function mount($id = 0)
    {
        // Initialize the doctorId with the provided id or default to 0
        $this->doctorId = $id;
        // Initialize any properties or services needed for the component

    }
    public function render()
    {   

        $this->dataList = $this->philHealthProfFeeServices->listPatientsByDoctor($this->doctorId);


        
        return view('livewire.doctor.doctor-patients');
    }
}

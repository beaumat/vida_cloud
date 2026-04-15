<?php

namespace App\Livewire\Location;

use App\Services\ContactServices;
use App\Services\DoctorLocationServices;
use App\Services\LocationServices;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Doctor Location')]
class LocationDoctors extends Component
{

    public bool $refresh = false;
    public $dataList = [];
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public $doctorList = [];
    private $doctorLocationServices;
    private $locationServices;

    public function boot(DoctorLocationServices $doctorLocationServices, LocationServices $locationServices)
    {
        $this->doctorLocationServices = $doctorLocationServices;
        $this->locationServices = $locationServices;
    }
    public function mount(int $id)
    {
        $this->LOCATION_ID = $id;
        $data = $this->locationServices->get($id);

        if ($data) {
            $this->LOCATION_NAME = $data->NAME ?? '';
        }
    }

    public int $DOCTOR_ID;
    public function Add()
    {

        $this->validate(
            [
                'DOCTOR_ID' => 'required|integer|exists:contact,id'
            ],
            [],
            ['DOCTOR_ID' => 'Doctor']
        );


        $this->doctorLocationServices->Store(
            $this->LOCATION_ID,
            $this->DOCTOR_ID
        );
        $this->refresh = $this->refresh ? false : true;
        $this->DOCTOR_ID = 0;
    }
    public function Delete(int $ID)
    {
        $this->doctorLocationServices->Delete($this->LOCATION_ID, $ID);
        $this->refresh = $this->refresh ? false : true;
    }

    public function render()
    {

        $this->dataList = $this->doctorLocationServices->ViewList($this->LOCATION_ID);
        $this->doctorList = $this->doctorLocationServices->GetDoctorList($this->LOCATION_ID);

        return view('livewire.location.location-doctors');
    }
}

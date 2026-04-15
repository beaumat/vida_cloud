<?php

namespace App\Livewire\HemodialysisMachine;

use App\Models\MachineType;
use App\Services\HemodialysisMachineServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Hemodailysis Machine')]
class HemoMachineForm extends Component
{
    public int $ID;
    public string $CODE;
    public int $TYPE;
    public string $DESCRIPTION;
    public int $LOCATION_ID;
    public int $CAPACITY;
    public $typeList = [];
    public $locationList = [];
    private $hemodialysisMachineServices;
    private $userServices;
    private $locationServices;
    public function boot(
        HemodialysisMachineServices $hemodialysisMachineServices,
        UserServices $userServices,
        LocationServices $locationServices
    ) {
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id = null)
    {
        $this->typeList = MachineType::all();
        $this->locationList = $this->locationServices->getList();
        if (is_numeric($id)) {
            $data = $this->hemodialysisMachineServices->get($id);
            if ($data) {
                $this->ID = $data->ID;
                $this->CODE = $data->CODE;
                $this->TYPE = $data->TYPE;
                $this->DESCRIPTION = $data->DESCRIPTION;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->CAPACITY = $data->CAPACITY ?? 0;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceothershemo_machine')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->TYPE = 1;
        $this->DESCRIPTION = '';
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CAPACITY = 0;

    }

    public function save()
    {
        $this->validate(
            [
                'CODE' => 'required',
                'TYPE' => 'required',
                'LOCATION_ID' => 'required',
                'CAPACITY' => 'required'
            ],
            [],
            [
                'CODE' => 'Machine No.',
                'Machine Type',
                'LOCATION_ID' => 'Location',
                'CAPACITY' => 'Capacity'
            ]
        );

        try {

            if ($this->ID == 0) {
                $this->ID = $this->hemodialysisMachineServices->Store(
                    $this->CODE,
                    $this->TYPE,
                    $this->DESCRIPTION,
                    $this->LOCATION_ID,
                    $this->CAPACITY
                );
                return Redirect::route('maintenanceothershemo_machine_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->hemodialysisMachineServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->TYPE,
                    $this->DESCRIPTION,
                    $this->LOCATION_ID,
                    $this->CAPACITY
                );
                return Redirect::route('maintenanceothershemo_machine')->with('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

    }
    public function render()
    {
        return view('livewire.hemodialysis-machine.hemo-machine-form');
    }
}

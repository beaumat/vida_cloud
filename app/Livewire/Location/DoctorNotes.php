<?php

namespace App\Livewire\Location;

use App\Services\DoctorOrderDefaultServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Notes')]
class DoctorNotes extends Component
{
    public $dataList = [];
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public string $DESCRIPTION = '';

    public $editID  = null;
    public $editDescription  = '';
    private $doctorOrderDefaultServices;
    private $locationServices;
    public function boot(DoctorOrderDefaultServices $doctorOrderDefaultServices, LocationServices $locationServices)
    {
        $this->doctorOrderDefaultServices =  $doctorOrderDefaultServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id)
    {
        $this->DESCRIPTION = '';
        if (is_numeric($id)) {
            $data = $this->locationServices->get($id);
            if ($data) {

                $this->LOCATION_ID = $data->ID;
                $this->LOCATION_NAME = $data->NAME ?? '';
                return;
            }
        }
        $errorMessage = 'Error occurred: Record not found. ';
        return Redirect::route('maintenancesettingslocation')->with('error', $errorMessage);
    }
    public function delete(int $id)
    {

        $this->doctorOrderDefaultServices->Delete($id);
    }
    public function edit($id)
    {
        $data =  $this->doctorOrderDefaultServices->Get($id);
        if ($data) {
            $this->editID = $data->ID;
            $this->editDescription = $data->DESCRIPTION ?? '';
        }
    }
    public function update()
    {
        $this->validate([
            'editDescription' => 'required|string'
        ], [], [
            'editDescription' => 'description'
        ]);

        $this->doctorOrderDefaultServices->Update($this->editID, $this->editDescription, false, false);
        $this->cancel();
    }
    public function cancel()
    {
        $this->editID = null;
        $this->editDescription = '';
    }
    public function store()
    {
        $this->validate([
            'DESCRIPTION' => 'required|string'
        ], [], [
            'DESCRIPTION' => 'description'
        ]);

        try {
            $this->doctorOrderDefaultServices->Store($this->LOCATION_ID, $this->DESCRIPTION);
            $this->DESCRIPTION = '';
            session()->flash('message', 'Successfully added');
        } catch (\Throwable $th) {

            session()->flash('error', $th->getMessage());
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $this->dataList = $this->doctorOrderDefaultServices->getListByLocation($this->LOCATION_ID);
        return view('livewire.location.doctor-notes');
    }
}

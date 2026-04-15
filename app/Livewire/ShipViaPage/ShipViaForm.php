<?php

namespace App\Livewire\ShipViaPage;

use App\Models\ShipVia;
use App\Services\ShipViaServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('ShipVia - Form')]
class ShipViaForm extends Component
{

    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $shipVia = ShipVia::where('ID', $id)->first();

            if ($shipVia) {
                $this->ID = $shipVia->ID;
                $this->CODE = $shipVia->CODE;
                $this->DESCRIPTION = $shipVia->DESCRIPTION;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryship_via')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
    }

    public function save(ShipViaServices $shipViaServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:ship_via,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:50|unique:ship_via,description,' . $this->ID
            ],
            [],
            [
                'CODE' => 'Code',
                'Description' => 'Description'
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $shipViaServices->Store($this->CODE, $this->DESCRIPTION);
                session()->flash('message', 'Successfully created');
            } else {
                $shipViaServices->Update($this->ID, $this->CODE, $this->DESCRIPTION);
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.ship-via.ship-via-form');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

}

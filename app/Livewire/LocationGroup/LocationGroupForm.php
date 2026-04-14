<?php

namespace App\Livewire\LocationGroup;

use App\Models\LocationGroup;
use App\Services\LocationGroupServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location Group')]
class LocationGroupForm extends Component
{

    public int $ID;
    public string $NAME;
    public bool $INACTIVE;

    public function mount($id = null)
    {
 

        if (is_numeric($id)) {

            $locationGroup = LocationGroup::where('ID', $id)->first();

            if ($locationGroup) {
                $this->ID = $locationGroup->ID;
                $this->NAME = $locationGroup->NAME;
                $this->INACTIVE = $locationGroup->INACTIVE;

                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingslocation_group')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->INACTIVE = false;
    }


    public function save(LocationGroupServices $locationGroupServices)
    {
        $this->validate(
            [
                'NAME' => 'required|max:50|unique:location_group,name,' . $this->ID
            ],
            [],
            [
                'NAME' => 'Name'
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $locationGroupServices->Store( $this->NAME, $this->INACTIVE);
                session()->flash('message', 'Successfully created');
            } else {
                $locationGroupServices->Update($this->ID, $this->NAME, $this->INACTIVE);
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
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
        return view('livewire..location-group.location-group-form');
    }
}

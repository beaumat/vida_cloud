<?php

namespace App\Livewire\ManufacturerPage;

use App\Models\Manufacturers;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use App\Services\ManufacturerServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Manufacturer - Form')]
class ManufacturerForm extends Component
{
    public int $ID;
    public string $CODE;
    public string $NAME;

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $manufacturer = Manufacturers::where('ID', $id)->first();

            if ($manufacturer) {
                $this->ID = $manufacturer->ID;
                $this->CODE = $manufacturer->CODE;
                $this->NAME = $manufacturer->NAME;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventorymanufacturers')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->NAME = '';
    }


    public function save(ManufacturerServices $manufacturerServices)
    {
        $this->validate([
            'CODE' => 'required|max:10|unique:manufacturer,code,' . $this->ID,
            'NAME' => 'required|max:50|unique:manufacturer,name,' . $this->ID
        ],[],[
            'CODE' => 'Code',
            'Name' => 'Name'
        ]);

        try {
            if ($this->ID === 0) {
                $this->ID = $manufacturerServices->Store($this->CODE, $this->NAME);
                session()->flash('message', 'Successfully created');
            } else {
                $manufacturerServices->Update($this->ID, $this->CODE, $this->NAME);
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.manufacturer.manufacturer-form');
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

<?php

namespace App\Livewire\Shift;

use App\Services\ShiftServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Shift')]
class ShiftForm extends Component
{
    public int $ID;
    public string $NAME;
    public int $LINE_NO;
    private $shiftServices;
    public function boot(ShiftServices $shiftServices)
    {
        $this->shiftServices = $shiftServices;
    }
    public function mount($id = null)
    {
        try {
            if (is_numeric($id)) {
                $data = $this->shiftServices->get($id);
                if ($data) {
                    $this->ID = $data->ID;
                    $this->NAME = $data->NAME;
                    $this->LINE_NO = $data->LINE_NO;
                    return;
                }
                $errorMessage = 'Error occurred: Record not found. ';
                return Redirect::route('maintenanceothersshift')->with('message', $errorMessage);
            }

            $this->ID = 0;
            $this->NAME = '';
            $this->LINE_NO = 0;


        } catch (\Exception $e) {
            return Redirect::route('maintenanceothersshift')->with('message', $e->getMessage());
        }
    }
    public function save()
    {
        $this->validate(
            [
                'NAME' => 'required|max:50|unique:shift,name,' . $this->ID
            ],
            [],
            [
                'NAME' => 'Name'
            ]
        );

        try {
            if ($this->ID == 0) {
                $this->ID = $this->shiftServices->Store($this->NAME, $this->LINE_NO);
                return Redirect::route('maintenanceothersshift')->with('message', 'Successfully created');
            }
            $this->shiftServices->Update($this->ID, $this->NAME, $this->LINE_NO);
            return Redirect::route('maintenanceothersshift')->with('message', 'Successfully updated');
        } catch (\Exception $e) {
            return Redirect::route('maintenanceothersshift')->with('message', $e->getMessage());
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.shift.shift-form');
    }
}

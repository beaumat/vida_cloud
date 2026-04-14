<?php

namespace App\Livewire\Requirement;

use App\Services\RequirementServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Requirements')]
class RequirementForm extends Component
{
    public int $ID;
    public string $DESCRIPTION;
    public bool $INACTIVE;
    private $requirementServices;
    public function boot(RequirementServices $requirementServices)
    {
        $this->requirementServices = $requirementServices;
    }
    public function mount($id = null)
    {
        try {
            if (is_numeric($id)) {
                $data = $this->requirementServices->get($id);
                if ($data) {
                    $this->ID = $data->ID;
                    $this->DESCRIPTION = $data->DESCRIPTION;
                    $this->INACTIVE = $data->INACTIVE;
                    return;
                }
                $errorMessage = 'Error occurred: Record not found. ';
                return Redirect::route('maintenanceothersrequirement')->with('message', $errorMessage);
            }

            $this->ID = 0;
            $this->DESCRIPTION = '';
            $this->INACTIVE = false;


        } catch (\Exception $e) {
            return Redirect::route('maintenanceothersrequirement')->with('message', $e->getMessage());
        }
    }
    public function save()
    {
        $this->validate(
            [
                'DESCRIPTION' => 'required|max:60|unique:requirement,description,' . $this->ID
            ],
            [],
            [
                'DESCRIPTION' => 'Description'
            ]
        );

        try {



            if ($this->ID == 0) {
                $this->ID = $this->requirementServices->Store($this->DESCRIPTION, $this->INACTIVE);
                return Redirect::route('maintenanceothersrequirement')->with('message', 'Successfully created');
            }
            $this->requirementServices->Update($this->ID, $this->DESCRIPTION, $this->INACTIVE);
            return Redirect::route('maintenanceothersrequirement')->with('message', 'Successfully updated');
        } catch (\Exception $e) {
            return Redirect::route('maintenanceothersrequirement')->with('message', $e->getMessage());
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
        return view('livewire.requirement.requirement-form');
    }
}

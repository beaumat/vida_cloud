<?php

namespace App\Livewire\Patient;

use App\Services\PatientConfinementServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class ConfinementRecord extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Reactive]
    public int $PATIENT_ID;
    public $DATE_START;
    public $DATE_END;
    public string $DESCRIPTION;
    public $E_ID = null;
    public $E_DATE_START;
    public $E_DATE_END;
    public string $E_DESCRIPTION;

    public $search;

    private $patientConfinementServices;
    public function boot(PatientConfinementServices $patientConfinementServices)
    {
        $this->patientConfinementServices = $patientConfinementServices;
    }
    public function cancel()
    {
        $this->E_ID = null;
        $this->E_DATE_START = null;
        $this->E_DATE_END = null;
        $this->E_DESCRIPTION = "";
    }
    public function save()
    {

        $this->validate(
            [
                'DATE_START'    => 'required|date',
                'DESCRIPTION'   => 'required|string',
                'PATIENT_ID'    => 'required|numeric',
            ],
            [],
            [
                'DATE_START'    => 'Date Confinement',
                'DESCRIPTION'   => 'Notes'
            ]
        );

        try {
            $this->patientConfinementServices->store(
                $this->DATE_START,
                $this->DATE_END,
                $this->DESCRIPTION,
                $this->PATIENT_ID
            );
            session()->flash('message', 'Successfully Added!');

            $this->DATE_START = "";
            $this->DATE_END = "";
            $this->DESCRIPTION = "";
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function update()
    {
        try {
            $this->patientConfinementServices->update(
                $this->E_ID,
                $this->E_DATE_START,
                $this->E_DATE_END,
                $this->E_DESCRIPTION
            );
            $this->cancel();
            session()->flash('message', 'Successfully updated!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function edit(int $ID)
    {
        $data = $this->patientConfinementServices->get($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_DATE_START = $data->DATE_START ?? '';
            $this->E_DATE_END = $data->DATE_END ?? '';
            $this->E_DESCRIPTION = $data->DESCRIPTION ?? '';
        }
    }

    public function delete(int $ID)
    {
        $this->patientConfinementServices->delete($ID);
        
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

        $data = $this->patientConfinementServices->list($this->PATIENT_ID ?? 0, $this->search);

        return view('livewire.patient.confinement-record', ['dataList' => $data]);
    }
}

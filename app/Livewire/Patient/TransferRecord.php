<?php

namespace App\Livewire\Patient;

use App\Services\PatientTransferServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class TransferRecord extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Reactive]
    public int $PATIENT_ID;
    public string $NOTES;
    public $DATE_TRANSFER;
    public string $E_NOTES;
    public $E_DATE_TRANSFER;
    public $E_ID = null;


    public $search;
    private $patientTransferServices;
    public function boot(PatientTransferServices $patientTransferServices)
    {
        $this->patientTransferServices = $patientTransferServices;
    }
    public function save()
    {

        $this->validate([
            'DATE_TRANSFER' => 'required|date',
            'NOTES'     => 'required',
            'PATIENT_ID'        => 'required|exists:contact,id'
        ], [], [
            'DATE_TRANSFER'     => 'Date Transfer',
            'NOTES'             => 'Notes',
            'PATIENT_ID'        => 'Patient'
        ]);


        try {

            $this->patientTransferServices->store($this->PATIENT_ID, $this->DATE_TRANSFER, $this->NOTES);
            $this->DATE_TRANSFER = null;
            $this->NOTES = "";
            session()->flash('message', 'Successful added!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function update()
    {

        $this->validate([
            'E_DATE_TRANSFER'       => 'required|date',
            'E_NOTES'               => 'required'

        ], [], [
            'E_DATE_TRANSFER'     => 'Date Transfer',
            'E_NOTES'             => 'Notes',
        ]);


        try {
            $this->patientTransferServices->update($this->E_ID, $this->E_DATE_TRANSFER, $this->E_NOTES);
            $this->cancel();
            session()->flash('message', 'Successful updated!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function edit(int $ID)
    {
        $data =  $this->patientTransferServices->get($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_NOTES = $data->NOTES ?? '';
            $this->E_DATE_TRANSFER = $data->DATE_TRANSFER;
        }
    }
    public function delete(int $ID)
    {
        $this->patientTransferServices->delete($ID);
    }
    public function cancel()
    {
        $this->E_ID = null;
        $this->E_NOTES;
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
        $dataList = $this->patientTransferServices->list($this->PATIENT_ID, 30, $this->search);
        return view('livewire.patient.transfer-record', ['dataList' => $dataList]);
    }
}

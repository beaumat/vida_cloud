<?php
namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class NurseNotes extends Component
{

    public int $HEMO_ID;
    public string $PATIENT_NAME;
    public int $ID;
    public string $TIME;
    public string $BP_1;
    public string $BP_2;
    public string $HR;
    public string $BFR;
    public string $AP;
    public string $VP;
    public string $TFP;
    public string $TMP;
    public string $HEPARIN;
    public string $FLUSHING;
    public string $NOTES;
    public $EDIT_ID = null;
    public string $EDIT_TIME;
    public string $EDIT_BP_1;
    public string $EDIT_BP_2;
    public string $EDIT_HR;
    public string $EDIT_BFR;
    public string $EDIT_AP;
    public string $EDIT_VP;
    public string $EDIT_TFP;
    public string $EDIT_TMP;
    public string $EDIT_HEPARIN;
    public string $EDIT_FLUSHING;
    public string $EDIT_NOTES;

    public $dataList       = [];
    public bool $showModal = false;
    private $hemoServices;
    public int $STATUS_ID = 0;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }

    #[On('open-nurse-notes')]
    public function openModal($result)
    {
        $this->ClearEntry();
        $this->HEMO_ID      = $result['HEMO_ID'];
        $this->PATIENT_NAME = $result['PATIENT_NAME'] ?? '';
        $hemoData           = $this->hemoServices->get($this->HEMO_ID);
        if ($hemoData) {
            $this->STATUS_ID = $hemoData->STATUS_ID;
        }
        $this->showModal = true;
    }
    public function closeModal()
    {

        $this->showModal = false;
    }

    public function edit(int $ID)
    {
        $data = $this->hemoServices->GetNotes($ID);
        if ($data) {
            $this->EDIT_ID       = $data->ID;
            $this->EDIT_TIME     = $data->TIME;
            $this->EDIT_BP_1     = $data->BP_1;
            $this->EDIT_BP_2     = $data->BP_2;
            $this->EDIT_HR       = $data->HR;
            $this->EDIT_BFR      = $data->BFR;
            $this->EDIT_AP       = $data->AP;
            $this->EDIT_VP       = $data->VP;
            $this->EDIT_TFP      = $data->TFP;
            $this->EDIT_TMP      = $data->TMP;
            $this->EDIT_HEPARIN  = $data->HEPARIN;
            $this->EDIT_FLUSHING = $data->FLUSHING;
            $this->EDIT_NOTES    = $data->NOTES;
        }
    }
    public function cancel()
    {
        $this->EDIT_ID = null;
    }

    public function ClearEntry()
    {

        $this->TIME     = '';
        $this->BP_1     = '';
        $this->BP_2     = '';
        $this->HR       = '';
        $this->BFR      = '';
        $this->AP       = '';
        $this->VP       = '';
        $this->TFP      = '';
        $this->TMP      = '';
        $this->HEPARIN  = '';
        $this->FLUSHING = '';
        $this->NOTES    = '';
    }
    public function save()
    {

        $this->validate([
            'TIME' => 'required|string',
            'BP_1' => 'required|string',
            'BP_2' => 'required|string',
            'HR'   => 'required|string',
            'BFR'  => 'required|string',
            'TFP'  => 'required|string',
            'TMP'  => 'required|string',

        ], [], [
            'TIME' => 'Time',
            'BP_1' => 'BP[1]',
            'BP_2' => 'BP[2]',
            'HR'   => 'HR',
            'BFR'  => 'BFR',
            'TFP'  => 'TFR',
            'TMP'  => 'TMP',

        ]);

        try {

            $this->hemoServices->StoreNotes(
                $this->HEMO_ID,
                $this->TIME,
                $this->BP_1 ?? '',
                $this->BP_2 ?? '',
                $this->HR ?? '',
                $this->BFR ?? '',
                $this->AP ?? '',
                $this->VP ?? '',
                $this->TFP,
                $this->TMP,
                $this->HEPARIN,
                $this->FLUSHING,
                $this->NOTES
            );

            $this->ClearEntry();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function update()
    {

        $this->validate([
            'EDIT_TIME' => 'required|string',
            'EDIT_BP_1' => 'required|string',
            'EDIT_BP_2' => 'required|string',
            'EDIT_HR'   => 'required|string',
            'EDIT_BFR'  => 'required|string',
            'EDIT_TFP'  => 'required|string',
            'EDIT_TMP'  => 'required|string',

        ], [], [
            'EDIT_TIME'     => 'Time',
            'EDIT_BP_1'     => 'BP[1]',
            'EDIT_BP_2'     => 'BP[2]',
            'EDIT_HR'       => 'HR',
            'EDIT_BFR'      => 'BFR',
            'EDIT_AP'       => 'AP',
            'EDIT_VP'       => 'VP',
            'EDIT_TFP'      => 'TFR',
            'EDIT_TMP'      => 'TMP',
            'EDIT_HEPARIN'  => 'HEPARIN',
            'EDIT_FLUSHING' => 'FLUSHING',
            'EDIT_NOTES'    => 'NOTES',
        ]);

        try {

            $this->hemoServices->UpdateNotes(
                $this->EDIT_ID,
                $this->HEMO_ID,
                $this->EDIT_TIME,
                $this->EDIT_BP_1,
                $this->EDIT_BP_2,
                $this->EDIT_HR,
                $this->EDIT_BFR,
                $this->EDIT_AP,
                $this->EDIT_VP,
                $this->EDIT_TFP,
                $this->EDIT_TMP,
                $this->EDIT_HEPARIN,
                $this->EDIT_FLUSHING,
                $this->EDIT_NOTES
            );
            $this->cancel();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function delete($ID)
    {
        try {

            $this->hemoServices->DeleteNotes($ID, $this->HEMO_ID);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
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

        if ($this->showModal) {
            $this->dataList = $this->hemoServices->ListNotes($this->HEMO_ID);
        }

        return view('livewire.hemodialysis.nurse-notes');
    }
}

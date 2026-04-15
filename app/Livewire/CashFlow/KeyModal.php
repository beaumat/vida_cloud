<?php

namespace App\Livewire\CashFlow;

use App\Services\CashFlowServices;
use Livewire\Attributes\On;
use Livewire\Component;

class KeyModal extends Component
{


    public int $ID;
    public int $ACCOUNT_BASE;
    public $baseList = [];
    public string $ACCOUNT_KEY;
    public bool $DEBIT_DEFAULT;
    public int $LINE_NO;
    public bool $INACTIVE;
    public int $CS_FLOW_DETAILS_ID;
    public $showModal = false;
    public string $NAME;
    private $cashFlowServices;
    public function boot(CashFlowServices $cashFlowServices)
    {
        $this->cashFlowServices = $cashFlowServices;
    }
    public function save()
    {

        $this->validate(
            [
                'NAME'                  => 'required|string|min:6',
                'ACCOUNT_BASE'          => 'required|numeric',
                'ACCOUNT_KEY'           => 'required',
                'DEBIT_DEFAULT'         => 'required',
                'LINE_NO'               => 'required|numeric',
                'INACTIVE'              => 'required',
                'CS_FLOW_DETAILS_ID'    => 'required'
            ],
            [],
            []
        );

        if ($this->ID > 0) {

            $this->cashFlowServices->UpdateKey(
                $this->ID,
                $this->ACCOUNT_BASE,
                $this->ACCOUNT_KEY,
                $this->DEBIT_DEFAULT,
                $this->LINE_NO,
                $this->INACTIVE,
                $this->NAME
            );
            $this->closeModal();
            return;
        }

        $this->cashFlowServices->StoreKey(
            $this->CS_FLOW_DETAILS_ID,
            $this->ACCOUNT_BASE,
            $this->ACCOUNT_KEY,
            $this->DEBIT_DEFAULT,
            $this->LINE_NO,
            $this->NAME
        );
        $this->closeModal();
    }
    #[On('open-cf-key')]
    public function openModal($result)
    {
        $this->showModal = true;
        $this->baseList = $this->cashFlowServices->ACCOUNT_BASE_LIST();
        $dataID = $result['ID'] ?? 0;
        $this->CS_FLOW_DETAILS_ID = $result['CS_FLOW_DETAILS_ID'];

        if ($dataID > 0) {
            $data = $this->cashFlowServices->GetKey($dataID);
            if ($data) {
                $this->ID = $data->ID;
                $this->CS_FLOW_DETAILS_ID = $data->CS_FLOW_DETAILS_ID;
                $this->DEBIT_DEFAULT = $data->DEBIT_DEFAULT;
                $this->ACCOUNT_KEY = $data->ACCOUNT_KEY;
                $this->ACCOUNT_BASE = $data->ACCOUNT_BASE;
                $this->LINE_NO = $data->LINE_NO;
                $this->INACTIVE = $data->INACTIVE;
                $this->NAME = $data->NAME;
                return;
            }
        }

        $this->ID = 0;
        $this->LINE_NO = 0;
        $this->INACTIVE = false;
        $this->ACCOUNT_BASE = 0;
        $this->ACCOUNT_KEY  = '';
        $this->DEBIT_DEFAULT = true;
        $this->NAME = '';
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('refresh-generate');
    }
    public function render()
    {
        return view('livewire.cash-flow.key-modal');
    }
}

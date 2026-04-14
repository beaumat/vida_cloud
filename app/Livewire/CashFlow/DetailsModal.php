<?php

namespace App\Livewire\CashFlow;

use App\Services\CashFlowServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailsModal extends Component
{
    public int $ID;
    public int $CF_HEADER_ID;
    public string $NAME;
    public int $LINE_NO;
    public bool $INACTIVE;
    public bool $IS_TOTAL = false;
    public bool $showModal = false;
    private $cashFlowServices;
    public function boot(CashFlowServices $cashFlowServices)
    {
        $this->cashFlowServices = $cashFlowServices;
    }
    #[On('open-cf-details')]
    public function openModal($result)
    {
        $this->showModal = true;
        $dataID = $result['ID'] ?? 0;
        $this->CF_HEADER_ID = $result['CF_HEADER_ID'];

        if ($dataID > 0) {
            $data = $this->cashFlowServices->GetDetails($dataID);
            if ($data) {
                $this->ID = $data->ID;
                $this->CF_HEADER_ID = $data->CF_HEADER_ID;
                $this->NAME = $data->NAME;
                $this->LINE_NO = $data->LINE_NO;
                $this->INACTIVE = $data->INACTIVE;
                $this->IS_TOTAL = $data->IS_TOTAL;
                return;
            }
        }
        $this->ID = 0;
        $this->LINE_NO = 0;
        $this->INACTIVE = false;
        $this->NAME = '';
        $this->IS_TOTAL = false;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('refresh-generate');
    }
    public function save()
    {
        $this->validate(
            [
                'NAME'          => 'required|min:6',
                'CF_HEADER_ID'  => 'required|numeric',
                'LINE_NO'       => 'required|numeric',
                'INACTIVE'      => 'required',
            ],
            [],
            [
                'NAME'          => 'Name',
                'CF_HEADER_ID'  => 'CF_HEADER_ID',
                'LINE_NO'       => 'Line no.',
                'INACTIVE'      => 'Inactive',
            ]
        );


        try {

            if ($this->ID >  0) {
                $this->cashFlowServices->UpdateDetails($this->ID, $this->NAME, $this->LINE_NO, $this->INACTIVE, $this->IS_TOTAL);
                $this->closeModal();
                return;
            }

            $this->cashFlowServices->StoreDetails($this->CF_HEADER_ID, $this->NAME,  $this->LINE_NO, $this->IS_TOTAL);
            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.cash-flow.details-modal');
    }
}

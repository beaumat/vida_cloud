<?php

namespace App\Livewire\StockTransfer;

use App\Services\StockTransferServices;
use Livewire\Attributes\On;
use Livewire\Component;

class StockReceivedForm extends Component
{
    public $itemList = [];
    public $stockTransfer;
    public int $STOCK_TRANSFER_ID;
    public $showModal = false;
    private $stockTransferServices;
    public function boot(StockTransferServices $stockTransferServices)
    {
        $this->stockTransferServices = $stockTransferServices;
    }
    #[On('open-stock-transfer')]
    public function openModal($result)
    {
        $this->STOCK_TRANSFER_ID = $result['STOCK_TRANSFER_ID'];
        $this->stockTransfer = $this->stockTransferServices->Get($this->STOCK_TRANSFER_ID);
        $this->itemList = $this->stockTransferServices->ItemView($this->STOCK_TRANSFER_ID);
        $this->showModal = true;

    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openStockTransfer(int $STOCK_TRANSFER_ID)
    {
        $result = [
            'STOCK_TRANSFER' => $STOCK_TRANSFER_ID
        ];

        $this->dispatch('open-stock-transfer', result: $result);
    }

    public function render()
    {
        return view('livewire.stock-transfer.stock-received-form');
    }
}

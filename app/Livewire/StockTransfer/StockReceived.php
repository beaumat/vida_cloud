<?php

namespace App\Livewire\StockTransfer;

use App\Services\LocationServices;
use App\Services\StockTransferServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


#[Title('Stock Received')]
class StockReceived extends Component
{

    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $stockTransferServices;
    private $locationServices;
    private $userServices;

    public function boot(StockTransferServices $stockTransferServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->stockTransferServices = $stockTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;

    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function viewDetails(int $STOCK_TRANSFER_ID) {

        $result = [
            'STOCK_TRANSFER_ID' => $STOCK_TRANSFER_ID
        ];

        $this->dispatch('open-stock-transfer', result: $result);
    }
    public function render()
    {

        $dataList = $this->stockTransferServices->getStockTransferReceiver($this->search, $this->locationid, $this->perPage);
        return view('livewire.stock-transfer.stock-received', ['dataList' => $dataList]);
    }
}

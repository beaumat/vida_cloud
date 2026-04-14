<?php

namespace App\Livewire\StockTransfer;

use App\Services\AccountJournalServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\StockTransferServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Stock Transfer')]
class StockTransferList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $stockTransferServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    public function boot(
        StockTransferServices $stockTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $itemInventoryServices
    ) {
        $this->stockTransferServices = $stockTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    private function DeleteEntry(int $id)
    {
        $data = $this->stockTransferServices->Get($id);
        if ($data) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->stockTransferServices->object_type_stock_transfer, $data->ID);
            $this->accountJournalServices->DeleteJournal($data->ACCOUNT_ID, $data->LOCATION_ID, $JOURNAL_NO, 0, $data->ID, $this->stockTransferServices->object_type_stock_transfer, $data->DATE, 1);
            $this->accountJournalServices->DeleteJournal($data->ACCOUNT_ID, $data->TRANSFER_TO_ID, $JOURNAL_NO, 0, $data->ID, $this->stockTransferServices->object_type_stock_transfer, $data->DATE, 0);
            $stItem = $this->stockTransferServices->ItemView($id);
            foreach ($stItem as $list) {
                // Jounral
                $this->accountJournalServices->DeleteJournal($list->ASSET_ACCOUNT_ID, $data->LOCATION_ID, $JOURNAL_NO, 0, $list->ID, $this->stockTransferServices->object_type_stock_transfer_items, $data->DATE, 1);
                $this->accountJournalServices->DeleteJournal($list->ASSET_ACCOUNT_ID, $data->TRANSFER_TO_ID, $JOURNAL_NO, 0, $list->ID, $this->stockTransferServices->object_type_stock_transfer_items, $data->DATE, 0);

                // Inventory
                $this->itemInventoryServices->DeleteInv($list->ITEM_ID, $data->LOCATION_ID, $this->stockTransferServices->document_type_id, $list->ID, $data->DATE);
                $this->itemInventoryServices->RecomputedOnhand($list->ITEM_ID, $data->LOCATION_ID, $data->DATE);
             
                $this->itemInventoryServices->DeleteInv($list->ITEM_ID, $data->TRANSFER_TO_ID, $this->stockTransferServices->document_type_id, $list->ID, $data->DATE);
                $this->itemInventoryServices->RecomputedOnhand($list->ITEM_ID, $data->TRANSFER_TO_ID, $data->DATE);
            }
        }

    }
    public function delete($id)
    {

        $data = $this->stockTransferServices->Get($id);
        if ($data) {

            if ($data->STATUS == 0 || $data->STATUS == 16) {
                DB::beginTransaction();
                try {

                    if ($data->STATUS == 16) {
                        $this->DeleteEntry($id);
                    }

                    $this->stockTransferServices->Delete($id);
                    DB::commit();
                    session()->flash('message', 'Successfully deleted.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorMessage = 'Error occurred: ' . $e->getMessage();
                    session()->flash('error', $errorMessage);
                }
                return;
            }

            session()->flash('error', 'Invalid. this file cannot be deleted.');
        }
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
    public function render()
    {
        $dataList = $this->stockTransferServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.stock-transfer.stock-transfer-list', ['dataList' => $dataList]);
    }
 
}

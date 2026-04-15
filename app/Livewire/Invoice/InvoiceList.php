<?php
namespace App\Livewire\Invoice;

use App\Services\AccountJournalServices;
use App\Services\InvoiceServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Invoice')]
class InvoiceList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $perPage        = 15;
    public int $locationid;
    public $locationList = [];
    private $invoiceServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;

    private $itemInventoryServices;
    private $serviceChargeServices;
    public function boot(
        InvoiceServices $invoiceServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $itemInventoryServices,
        ServiceChargeServices $serviceChargeServices
    ) {
        $this->invoiceServices        = $invoiceServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->serviceChargeServices  = $serviceChargeServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    private function deleteItem(int $Id, $INVOICE_ID, $JOURNAL_NO)
    {
        $invoiceDate = $this->invoiceServices->get($INVOICE_ID);
        if ($invoiceDate) {
            $invoiceItemData = $this->invoiceServices->ItemGet($Id, $INVOICE_ID);
            if ($invoiceItemData) {
                // Inventory
                $this->itemInventoryServices->InventoryModify(
                    $invoiceItemData->ITEM_ID,
                    $invoiceDate->LOCATION_ID,
                    $Id,
                    $this->invoiceServices->document_type_id,
                    $invoiceDate->DATE,
                    0,
                    0,
                    0
                );

                $this->itemInventoryServices->RecomputedOnhand(
                    $invoiceItemData->ITEM_ID,
                    $invoiceDate->LOCATION_ID,
                    $invoiceDate->DATE
                );

                // INCOME_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->INCOME_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
                // COGS_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->COGS_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    0,

                );
                // ASSET_ACCOUNT_ID
                $this->accountJournalServices->DeleteJournal(
                    $invoiceItemData->ASSET_ACCOUNT_ID ?? 0,
                    $invoiceDate->LOCATION_ID,
                    $JOURNAL_NO,
                    $invoiceItemData->ITEM_ID,
                    $Id,
                    $this->invoiceServices->object_type_invoice_item,
                    $invoiceDate->DATE,
                    1,

                );
            }
        }
    }
    public function delete($INVOICE_ID)
    {
        try {
            DB::beginTransaction();
            $data = $this->invoiceServices->get($INVOICE_ID);
            if ($data) {
                if ($data->STATUS == 15 || $data->STATUS == 16) {
                    //Main
                    $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $INVOICE_ID);
                    $this->accountJournalServices->DeleteJournal(
                        $data->ACCOUNTS_RECEIVABLE_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $INVOICE_ID,
                        $this->invoiceServices->object_type_invoice,
                        $data->DATE,
                        0,

                    );
                    //Tax
                    $this->accountJournalServices->DeleteJournal(
                        $data->OUTPUT_TAX_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $INVOICE_ID,
                        $this->invoiceServices->object_type_invoice,
                        $data->DATE,
                        1,

                    );
                    $dataitem = $this->invoiceServices->ItemView($INVOICE_ID);

                    foreach ($dataitem as $list) {
                        // delete Item
                        $this->deleteItem($list->ID, $INVOICE_ID, $JOURNAL_NO);
                    }
                }
            }

            // Delete main
            $this->invoiceServices->Delete($INVOICE_ID);
            $this->serviceChargeServices->RemovingUpdateInvoiceID($INVOICE_ID);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
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
    #[On('quick-paid-reload')]
    public function render()
    {
        $dataList = $this->invoiceServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.invoice.invoice-list', ['dataList' => $dataList]);
    }

}

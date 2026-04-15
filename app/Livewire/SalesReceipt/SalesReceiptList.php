<?php
namespace App\Livewire\SalesReceipt;

use App\Services\AccountJournalServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\SalesReceiptServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sales Receipt')]
class SalesReceiptList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $perPage        = 25;
    public int $locationid;
    public $dateEntry;

    public $locationList = [];
    private $salesReceiptServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $itemInventoryServices;
    private $patientPaymentServices;

    public function boot(
        SalesReceiptServices $salesReceiptServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        ItemInventoryServices $itemInventoryServices,
        PatientPaymentServices $patientPaymentServices
    ) {
        $this->salesReceiptServices   = $salesReceiptServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
        $this->dateEntry    = null;
    }
    public function deleteItem(int $Id, int $SALES_RECEIPT_ID, int $JOURNAL_NO)
    {

        $sr = $this->salesReceiptServices->get($SALES_RECEIPT_ID);
        if ($sr) {
            $srItem = $this->salesReceiptServices->ItemGet($Id, $SALES_RECEIPT_ID, );
            if ($srItem) {
                // Inventory
                $this->itemInventoryServices->InventoryModify(
                    $srItem->ITEM_ID,
                    $sr->LOCATION_ID,
                    $Id,
                    $this->salesReceiptServices->document_type_id,
                    $sr->DATE,
                    0,
                    0,
                    0
                );

                // INCOME_ACCOUNT_ID

                if ($srItem->INCOME_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->INCOME_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        1,

                    );
                }

                // COGS_ACCOUNT_ID
                if ($srItem->COGS_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->COGS_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        0,
                    );
                }

                // ASSET_ACCOUNT_ID
                if ($srItem->ASSET_ACCOUNT_ID) {
                    $this->accountJournalServices->DeleteJournal(
                        $srItem->ASSET_ACCOUNT_ID,
                        $sr->LOCATION_ID,
                        $JOURNAL_NO,
                        $srItem->ITEM_ID,
                        $Id,
                        $this->salesReceiptServices->object_type_sales_receipt_items,
                        $sr->DATE,
                        1,

                    );
                }
            }
        }
    }
    public function delete($SR_ID)
    {
        try {
            DB::beginTransaction();
            $data = $this->salesReceiptServices->get($SR_ID);
            if ($data) {
                if ($data->STATUS == 15 || $data->STATUS == 16) {
                    $JOURNAL_NO = $this->accountJournalServices->getRecord(
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $SR_ID
                    );
                    //Main
                    $this->accountJournalServices->DeleteJournal(
                        $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $SR_ID,
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $data->DATE,
                        0,

                    );

                    //Tax
                    $this->accountJournalServices->DeleteJournal(
                        $data->OUTPUT_TAX_ACCOUNT_ID ?? 0,
                        $data->LOCATION_ID,
                        $JOURNAL_NO,
                        $data->CUSTOMER_ID,
                        $SR_ID,
                        $this->salesReceiptServices->object_type_sales_receipt,
                        $data->DATE,
                        1,

                    );

                    $dataItem = $this->salesReceiptServices->ItemView($SR_ID);
                    foreach ($dataItem as $list) {
                        $this->deleteItem($list->ID, $SR_ID, $JOURNAL_NO);
                    }
                    $PP_ID = $this->patientPaymentServices->GetCustomerRef(false, $SR_ID);
                    if ($PP_ID > 0) {
                        $this->patientPaymentServices->CustomerRef($PP_ID, false, 0);
                    }
                }
            }

            $this->salesReceiptServices->Delete($SR_ID);
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
    public function render()
    {
        $dataList = $this->salesReceiptServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->dateEntry,

        );

        return view('livewire.sales-receipt.sales-receipt-list', ['dataList' => $dataList]);
    }
}

<?php

namespace App\Livewire\Bills;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Billing')]
class BillingList extends Component
{

    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $billingServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    private $objectServices;
    private $itemInventoryServices;
    private $documentTypeServices;
    public function boot(
        BillingServices $billingServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        ObjectServices $objectServices,
        ItemInventoryServices $itemInventoryServices,
        DocumentTypeServices $documentTypeServices
    ) {
        $this->billingServices = $billingServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->objectServices = $objectServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->documentTypeServices = $documentTypeServices;
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
    private function DeleteJournal(int $BILL_ID)
    {
        $billData = $this->billingServices->get($BILL_ID);
        if ($billData) {
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $BILL_ID);

            $billItem = $this->billingServices->ItemView($BILL_ID);
            foreach ($billItem as $list) {
                //GetJournal
                $this->accountJournalServices->DeleteJournal(
                    $list->ACCOUNT_ID,
                    $billData->LOCATION_ID,
                    $JOURNAL_NO,
                    $list->ITEM_ID,
                    $list->ID,
                    $this->billingServices->object_type_map_bill_item,
                    $billData->DATE,
                    $list->AMOUNT >= 0 ? 0 : 1
                );
                //Inventory


                $this->itemInventoryServices->DeleteInv(
                    $list->ITEM_ID,
                    $billData->LOCATION_ID,
                    $this->billingServices->document_type_id,
                    $list->ID,
                    $billData->DATE
                );

                $this->itemInventoryServices->RecomputedOnhand(
                    $list->ITEM_ID,
                    $billData->LOCATION_ID,
                    $billData->DATE
                );
            }

            $billExpense = $this->billingServices->ExpenseView($BILL_ID);
            foreach ($billExpense as $list) {
                $this->accountJournalServices->DeleteJournal(
                    $list->ACCOUNT_ID,
                    $billData->LOCATION_ID,
                    $JOURNAL_NO,
                    $list->ACCOUNT_ID,
                    $list->ID,
                    $this->billingServices->object_type_map_bill_expenses,
                    $billData->DATE,
                    $list->AMOUNT >= 0 ? 0 : 1
                );
            }

            $this->accountJournalServices->DeleteJournal(
                $billData->ACCOUNTS_PAYABLE_ID,
                $billData->LOCATION_ID,
                $JOURNAL_NO,
                $billData->VENDOR_ID,
                $billData->ID,
                $this->billingServices->object_type_map_bill,
                $billData->DATE,
                1
            );

            $this->accountJournalServices->DeleteJournal(
                $billData->INPUT_TAX_ACCOUNT_ID,
                $billData->LOCATION_ID,
                $JOURNAL_NO,
                $billData->VENDOR_ID,
                $billData->ID,
                $this->billingServices->object_type_map_bill,
                $billData->DATE,
                0
            );
        }
    }
    public function delete(int $BILL_ID)
    {
        DB::beginTransaction();
        try {

            $this->DeleteJournal($BILL_ID);

            $isDelete = (bool) $this->billingServices->Delete($BILL_ID);
            if ($isDelete) {



                DB::commit();
                session()->flash('message', 'Successfully deleted.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        $dataList = $this->billingServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.bills.billing-list', ['dataList' => $dataList]);
    }
}

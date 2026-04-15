<?php
namespace App\Livewire\StockTransfer;

use App\Services\AccountJournalServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\StockTransferServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Stock Transfer')]
class StockTransferForm extends Component
{

    public int $openStatus = 0;
    public int $ID;
    public bool $hasItem = false;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $TRANSFER_TO_ID;
    public int $PREPARED_BY_ID;
    public float $AMOUNT;
    public float $RETAIL_VALUE;
    public string $NOTES;
    public int $ACCOUNT_ID;
    public $locationList = [];
    public $transferList = [];
    public $contactList  = [];
    public bool $Modify;
    public bool $transferReset = false;
    private $stockTransferServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $contactServices;
    private $documentTypeServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $systemSettingServices;
    public function boot(
        StockTransferServices $stockTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        ContactServices $contactServices,
        DocumentTypeServices $documentTypeServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->stockTransferServices  = $stockTransferServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->contactServices        = $contactServices;
        $this->documentTypeServices   = $documentTypeServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    public function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->contactList  = $this->contactServices->getList(2);
    }
    public function updatedLocationId()
    {
        $this->transferList = $this->locationServices->getListExcept($this->LOCATION_ID);
        $this->dispatch('clear-transfer');
    }
    #[On('clear-transfer')]
    public function clearTransfer()
    {
        $this->TRANSFER_TO_ID = 0;
        $this->transferReset  = $this->transferReset ? false : true;
    }

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->stockTransferServices->document_type_id;

            $data = $this->stockTransferServices->ItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute($data, $this->LOCATION_ID, $SOURCE_REF_TYPE, $this->DATE, false);
                $this->itemInventoryServices->InventoryExecute($data, $this->TRANSFER_TO_ID, $SOURCE_REF_TYPE, $this->DATE, true);
            }
            return true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    private function AccountJournal(): bool
    {
        try {

            $stockTransfer      = (int) $this->stockTransferServices->object_type_stock_transfer;
            $stockTransferItems = (int) $this->stockTransferServices->object_type_stock_transfer_items;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($stockTransfer, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($stockTransfer, $this->ID) + 1;
            }
            //Main
            $sourceData = $this->stockTransferServices->getStockTransferJournal_Source($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $sourceData,
                $this->LOCATION_ID,
                $stockTransfer,
                $this->DATE,
                "FROM"
            );

            $desData = $this->stockTransferServices->getStockTransferJournal_Des($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $desData,
                $this->TRANSFER_TO_ID,
                $stockTransfer,
                $this->DATE,
                "TO"
            );

            //Item
            $stItemCredit = $this->stockTransferServices->getStockTransferItemJournal_Credit($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $stItemCredit,
                $this->LOCATION_ID,
                $stockTransferItems,
                $this->DATE,
                "FROM"
            );

            $stItemDebit = $this->stockTransferServices->getStockTransferItemJournal_Debit($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $stItemDebit,
                $this->TRANSFER_TO_ID,
                $stockTransferItems,
                $this->DATE,
                "TO"
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function getPosted()
    {
        try {
            $count = (float) $this->stockTransferServices->CountItems($this->ID);
            if ($count == 0) {
                Session()->flash('error', 'No item to transfer');
                return;
            }

            DB::beginTransaction();
            if (! $this->ItemInventory()) {
                DB::rollBack();
                return;
            }

            if (! $this->AccountJournal()) {
                DB::rollBack();
                return;
            }
            $this->stockTransferServices->StatusUpdate($this->ID, 15);
            $this->STATUS = 15;
            DB::commit();
            Session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function getInfo($data)
    {
        $this->ID                 = $data->ID;
        $this->CODE               = $data->CODE;
        $this->DATE               = $data->DATE;
        $this->LOCATION_ID        = $data->LOCATION_ID;
        $this->transferList       = $this->locationServices->getListExcept($this->LOCATION_ID);
        $this->NOTES              = $data->NOTES ?? '';
        $this->TRANSFER_TO_ID     = $data->TRANSFER_TO_ID ?? 0;
        $this->transferReset      = $this->transferReset ? false : true;
        $this->AMOUNT             = $data->AMOUNT ?? 0;
        $this->RETAIL_VALUE       = $data->RETAIL_VALUE ?? 0;
        $this->PREPARED_BY_ID     = $data->PREPARED_BY_ID ?? 0;
        $this->ACCOUNT_ID         = $data->ACCOUNT_ID ?? 0;
        $this->STATUS             = $data->STATUS ?? 0;
        if($this->STATUS == 16) {
            $this->removeJournal();
        }
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->stockTransferServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);

                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companystock_transfer')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->Modify             = true;
        $this->ID                 = 0;
        $this->CODE               = '';
        $this->DATE               = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID        = $this->userServices->getLocationDefault();
        $this->transferList       = $this->locationServices->getListExcept($this->LOCATION_ID);
        $this->TRANSFER_TO_ID     = 0;
        $this->AMOUNT             = 0;
        $this->RETAIL_VALUE       = 0;
        $this->PREPARED_BY_ID     = 0;
        $this->NOTES              = '';
        $this->ACCOUNT_ID         = 100;
        $this->STATUS             = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {

        $this->Modify = true;
        if ($this->STATUS == 0) {
            $this->hasItem = $this->stockTransferServices->HasAlreadyItem($this->ID);
            return;
        }
        $this->hasItem = true;
    }
    public function save()
    {
        if ($this->TRANSFER_TO_ID == $this->LOCATION_ID) {
            session()->flash('Invalid transfer location');
            return;
        }

        try {
            if ($this->ID == 0) {

                $this->validate(
                    [
                        'DATE'           => 'required',
                        'LOCATION_ID'    => 'required|exists:location,id',
                        'TRANSFER_TO_ID' => 'required|not_in:0|exists:location,id',
                        'ACCOUNT_ID'     => 'required|not_in:0|exists:account,id',
                    ],
                    [],
                    [

                        'DATE'           => 'Date',
                        'LOCATION_ID'    => 'Location',
                        'TRANSFER_TO_ID' => 'Transfer To',
                        'ACCOUNT_ID'     => 'Account Transfer',

                    ]
                );
                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                DB::beginTransaction();

                $this->ID = $this->stockTransferServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->TRANSFER_TO_ID,
                    $this->NOTES,
                    $this->PREPARED_BY_ID,
                    $this->ACCOUNT_ID

                );
                DB::commit();
                return Redirect::route('companystock_transfer_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [

                        'CODE'           => 'required|max:20|unique:stock_transfer,code,' . $this->ID,
                        'DATE'           => 'required',
                        'LOCATION_ID'    => 'required',
                        'TRANSFER_TO_ID' => 'required|not_in:0',

                    ],
                    [],
                    [
                        'CODE'           => 'Reference No.',
                        'DATE'           => 'Date',
                        'LOCATION_ID'    => 'Location',
                        'TRANSFER_TO_ID' => 'Transfer To',
                    ]
                );

                DB::beginTransaction();
                $this->stockTransferServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->TRANSFER_TO_ID,
                    $this->NOTES,
                    $this->PREPARED_BY_ID
                );
                DB::commit();
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->stockTransferServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('companystock_transfer_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
      private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->stockTransferServices->object_type_stock_transfer, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function updateCancel()
    {
        $BA = $this->stockTransferServices->get($this->ID);
        if ($BA) {
            $this->getInfo($BA);
        }
        $this->Modify = false;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    #[On('update-amount')]
    public function updateAmount()
    {
        $data               = $this->stockTransferServices->GetSum($this->ID);
        $this->AMOUNT       = $data['AMOUNT'];
        $this->RETAIL_VALUE = $data['RETAIL_VALUE'];
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->stockTransferServices->object_type_stock_transfer, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }

    public function render()
    {
        return view('livewire.stock-transfer.stock-transfer-form');
    }
}

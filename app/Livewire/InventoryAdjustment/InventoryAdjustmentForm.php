<?php
namespace App\Livewire\InventoryAdjustment;

use App\Services\AccountJournalServices;
use App\Services\DocumentStatusServices;
use App\Services\InventoryAdjustmentServices;
use App\Services\InventoryAdjustmentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inventory Adjustment')]
class InventoryAdjustmentForm extends Component
{

    public int $openStatus = 0;
    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ADJUSTMENT_TYPE_ID;
    public string $NOTES;
    public int $ACCOUNT_ID;
    public $locationList       = [];
    public $adjustmentTypeList = [];
    public bool $Modify;
    public bool $transferReset = false;
    private $inventoryAdjustmentServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $inventoryAdjustmentTypeServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $systemSettingServices;
    public function boot(
        InventoryAdjustmentServices $inventoryAdjustmentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->inventoryAdjustmentServices     = $inventoryAdjustmentServices;
        $this->locationServices                = $locationServices;
        $this->userServices                    = $userServices;
        $this->documentStatusServices          = $documentStatusServices;
        $this->inventoryAdjustmentTypeServices = $inventoryAdjustmentTypeServices;
        $this->itemInventoryServices           = $itemInventoryServices;
        $this->accountJournalServices          = $accountJournalServices;
        $this->systemSettingServices           = $systemSettingServices;
    }
    public function LoadDropdown()
    {
        $this->locationList       = $this->locationServices->getList();
        $this->adjustmentTypeList = $this->inventoryAdjustmentTypeServices->getList();
    }

    private function ItemInventory(): bool
    {

        $SOURCE_REF_TYPE = (int) $this->inventoryAdjustmentServices->documentTypeMapId;
        $dataItem        = $this->inventoryAdjustmentServices->ItemInventory($this->ID);

        if ($dataItem) {
            return $this->itemInventoryServices->InventoryExecuteAdjustment(
                $dataItem,
                $this->LOCATION_ID,
                $SOURCE_REF_TYPE,
                $this->DATE
            );
        }
        return false;
    }
    private function AccountJournal(): bool
    {
        try {
            $invAdjustment      = (int) $this->inventoryAdjustmentServices->object_type_map_inventory_adjustment;
            $invAdjustmentItems = (int) $this->inventoryAdjustmentServices->object_type_map_inventory_adjustmentItems;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($invAdjustment, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($invAdjustment, $this->ID) + 1;
            } else {
                // reset
                $this->accountJournalServices->DeleteRecordJournal($JOURNAL_NO, $this->DATE, $this->LOCATION_ID);
            }

            //Main
            $dataSet = $this->inventoryAdjustmentServices->getInventoryAdjustmentJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $dataSet, $this->LOCATION_ID, $invAdjustment, $this->DATE);
            //Item
            $dataSetItem = $this->inventoryAdjustmentServices->getInventoryAdjustmentItemsJournal($this->ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $dataSetItem, $this->LOCATION_ID, $invAdjustmentItems, $this->DATE);
            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: [jd]' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function posted()
    {
        try {
            $count = (float) $this->inventoryAdjustmentServices->CountItems($this->ID);
            if ($count == 0) {
                Session()->flash('error', 'No item to adjust');
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

            $this->inventoryAdjustmentServices->StatusUpdate($this->ID, 15);
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
        $this->NOTES              = $data->NOTES ?? '';
        $this->ADJUSTMENT_TYPE_ID = $data->ADJUSTMENT_TYPE_ID ?? 0;
        $this->ACCOUNT_ID         = $data->ACCOUNT_ID ?? 0;
        $this->STATUS             = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        if($this->STATUS == 16) {
            $this->removeJournal();
        }
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->inventoryAdjustmentServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companyinventory_adjustment')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->Modify             = true;
        $this->ID                 = 0;
        $this->CODE               = '';
        $this->DATE               = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID        = $this->userServices->getLocationDefault();
        $this->ADJUSTMENT_TYPE_ID = 0;
        $this->NOTES              = '';
        $this->ACCOUNT_ID         = 0;
        $this->STATUS             = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->inventoryAdjustmentServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            return Redirect::route('companyinventory_adjustment_edit', ['id' => $this->ID]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->inventoryAdjustmentServices->object_type_map_inventory_adjustment, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }
    }
    public function save()
    {
        try {

            $this->validate(
                [
                    'CODE'               => $this->ID > 0 ? 'required|max:20|unique:inventory_adjustment,code,' . $this->ID : 'nullable',
                    'DATE'               => 'required|date_format:Y-m-d',
                    'LOCATION_ID'        => 'required|not_in:0|exists:location,id',
                    'ADJUSTMENT_TYPE_ID' => 'required|not_in:0|exists:inventory_adjustment_type,id',
                ],
                [],
                [
                    'CODE'               => 'Reference No.',
                    'DATE'               => 'Date',
                    'LOCATION_ID'        => 'Location',
                    'ADJUSTMENT_TYPE_ID' => 'Adjustment Type',
                ]
            );

            if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                return;
            }

            $this->ACCOUNT_ID = $this->inventoryAdjustmentTypeServices->getAccountId($this->ADJUSTMENT_TYPE_ID);
            if ($this->ACCOUNT_ID == 0) {
                session()->flash('error', 'Adjustment type account not found.');
                return;
            }
            DB::beginTransaction();
            if ($this->ID == 0) {

                $this->ID = $this->inventoryAdjustmentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->ADJUSTMENT_TYPE_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES
                );

                DB::commit();
                return Redirect::route('companyinventory_adjustment_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->inventoryAdjustmentServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ADJUSTMENT_TYPE_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES,

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
    public function delete()
    {
        if ($this->ID > 0) {
            DB::beginTransaction();
            try {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->inventoryAdjustmentServices->object_type_map_inventory_adjustment, $this->ID);
                if ($JOURNAL_NO > 0) {
                    $this->accountJournalServices->DeleteRecordJournal($JOURNAL_NO, $this->DATE, $this->LOCATION_ID);
                }
                $ItemList = $this->inventoryAdjustmentServices->ItemView($this->ID);
                foreach ($ItemList as $list) {
                    $this->itemInventoryServices->InventoryModify(
                        $list->ITEM_ID,
                        $this->LOCATION_ID,
                        $list->ID,
                        $this->inventoryAdjustmentServices->documentTypeMapId,
                        $this->DATE,
                        0,
                        0,
                        0
                    );
                }

                $this->inventoryAdjustmentServices->Delete($this->ID);
                DB::commit();
                return Redirect::route('companyinventory_adjustment')->with('message', 'Successfully deleted');
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', $th->getMessage());

            }

        }

    }
    public function updateCancel()
    {
        $BA = $this->inventoryAdjustmentServices->get($this->ID);

        if ($BA) {
            $this->getInfo($BA);
        }
        $this->Modify = false;
    }

    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->inventoryAdjustmentServices->object_type_map_inventory_adjustment,
            $this->ID
        );
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
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
        return view('livewire.inventory-adjustment.inventory-adjustment-form');
    }
}

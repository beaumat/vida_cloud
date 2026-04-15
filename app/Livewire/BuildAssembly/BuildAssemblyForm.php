<?php
namespace App\Livewire\BuildAssembly;

use App\Services\AccountJournalServices;
use App\Services\BuildAssemblyServices;
use App\Services\DocumentStatusServices;
use App\Services\DocumentTypeServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\SystemSettingServices;
use App\Services\UnitOfMeasureServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Build Assembly')]
class BuildAssemblyForm extends Component
{
    public int $ID;
    public int $ASSEMBLY_ITEM_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public string $NOTES;
    public int $ASSET_ACCOUNT_ID;
    public float $QUANTITY;
    public float $AMOUNT;
    public int $UNIT_ID;
    public int $BATCH_ID;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public $itemList     = [];
    public $locationList = [];
    public $unitList     = [];
    public bool $Modify;
    private $buildAssemblyServices;
    private $locationServices;
    private $itemServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $unitOfMeasureServices;
    private $itemInventoryServices;
    private $documentTypeServices;
    private $objectServices;
    private $accountJournalServices;

    public function boot(
        BuildAssemblyServices $buildAssemblyServices,
        LocationServices $locationServices,
        ItemServices $itemServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemInventoryServices $itemInventoryServices,
        DocumentTypeServices $documentTypeServices,
        ObjectServices $objectServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->buildAssemblyServices  = $buildAssemblyServices;
        $this->locationServices       = $locationServices;
        $this->itemServices           = $itemServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;

        $this->unitOfMeasureServices  = $unitOfMeasureServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->documentTypeServices   = $documentTypeServices;
        $this->objectServices         = $objectServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function LoadDropdown()
    {
        $this->itemList     = $this->itemServices->AssemblyItem();
        $this->locationList = $this->locationServices->getList();
        $this->unitList     = [];
    }

    public function updatedASSEMBLYITEMID()
    {
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ASSEMBLY_ITEM_ID);
        $dataItem       = $this->itemServices->get($this->ASSEMBLY_ITEM_ID);
        if ($dataItem) {
            $this->ASSET_ACCOUNT_ID = $dataItem->ASSET_ACCOUNT_ID ?? 0;
            return;
        }
        $this->ASSET_ACCOUNT_ID = 0;
    }

    private function getInfo($data)
    {
        $this->ID                 = $data->ID;
        $this->CODE               = $data->CODE;
        $this->DATE               = $data->DATE;
        $this->LOCATION_ID        = $data->LOCATION_ID;
        $this->ASSEMBLY_ITEM_ID   = $data->ASSEMBLY_ITEM_ID;
        $this->ASSET_ACCOUNT_ID   = $data->ASSET_ACCOUNT_ID ?? 0;
        $this->NOTES              = $data->NOTES ?? '';
        $this->AMOUNT             = $data->AMOUNT ?? 0;
        $this->BATCH_ID           = $data->BATCH_ID ?? 0;
        $this->QUANTITY           = $data->QUANTITY ?? 1;
        $this->UNIT_ID            = $data->UNIT_ID ?? 0;
        $this->STATUS             = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        if ($this->STATUS == 16) {
            $this->removeJournal();
        }
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $dataForm = $this->buildAssemblyServices->get($id);
            if ($dataForm) {
                $this->LoadDropdown();
                $this->getInfo($dataForm);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companybuild_assembly')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->Modify             = true;
        $this->ID                 = 0;
        $this->CODE               = '';
        $this->DATE               = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID        = $this->userServices->getLocationDefault();
        $this->ASSEMBLY_ITEM_ID   = 0;
        $this->ASSET_ACCOUNT_ID   = 21;
        $this->NOTES              = '';
        $this->AMOUNT             = 0;
        $this->QUANTITY           = 0;
        $this->BATCH_ID           = 0;
        $this->UNIT_ID            = 0;
        $this->STATUS             = 0;
        $this->STATUS_DESCRIPTION = "";
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {
        try {

            if ($this->ID == 0) {

                $this->validate(
                    [
                        'ASSEMBLY_ITEM_ID' => 'required|integer|exists:item,id',
                        'DATE'             => 'required|date_format:Y-m-d',
                        'LOCATION_ID'      => 'required|integer|exists:location,id',
                        'QUANTITY'         => 'required|not_in:0',
                        'ASSET_ACCOUNT_ID' => 'required|not_in:0|integer|exists:account,id',
                    ],
                    [],
                    [
                        'ASSEMBLY_ITEM_ID' => 'Aseembly Item',
                        'DATE'             => 'Date',
                        'LOCATION_ID'      => 'Location',
                        'QUANTITY'         => 'Quantity',
                        'ASSET_ACCOUNT_ID' => 'Asset Accounts',
                    ]
                );

                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ASSEMBLY_ITEM_ID, $this->UNIT_ID ?? 0);

                DB::beginTransaction();
                $this->ID = $this->buildAssemblyServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->ASSEMBLY_ITEM_ID,
                    $this->QUANTITY,
                    $this->BATCH_ID,
                    $this->UNIT_ID,
                    (float) $unitRelated['QUANTITY'],
                    $this->NOTES,
                    $this->ASSET_ACCOUNT_ID
                );
                DB::commit();
                return Redirect::route('companybuild_assembly_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [
                        'ASSEMBLY_ITEM_ID' => 'required|integer|exists:item,id',
                        'CODE'             => 'required|max:20|unique:build_assembly,code,' . $this->ID,
                        'DATE'             => 'required|date_format:Y-m-d',
                        'LOCATION_ID'      => 'required|integer|exists:location,id',
                        'QUANTITY'         => 'required|not_in:0',
                    ],
                    [],
                    [
                        'ASSEMBLY_ITEM_ID' => 'Aseembly Item',
                        'CODE'             => 'Reference No.',
                        'DATE'             => 'Date',
                        'LOCATION_ID'      => 'Location',
                        'QUANTITY'         => 'Quantity',
                    ]
                );
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ASSEMBLY_ITEM_ID, $this->UNIT_ID ?? 0);
                DB::beginTransaction();
                $this->AMOUNT = $this->buildAssemblyServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->ASSEMBLY_ITEM_ID,
                    $this->QUANTITY,
                    $this->BATCH_ID,
                    $this->UNIT_ID,
                    (float) $unitRelated['QUANTITY'],
                    $this->NOTES,
                    $this->LOCATION_ID
                );

                DB::commit();
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updateCancel()
    {
        $BA = $this->buildAssemblyServices->get($this->ID);

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

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = $this->buildAssemblyServices->document_type_id;

            $data = $this->buildAssemblyServices->AssemblyItemInventory($this->ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute(
                    $data,
                    $this->LOCATION_ID,
                    $SOURCE_REF_TYPE,
                    $this->DATE,
                    true
                );
            }

            $dataItem = $this->buildAssemblyServices->ItemInventory($this->ID);
            if ($dataItem) {
                $this->itemInventoryServices->InventoryExecute(
                    $dataItem,
                    $this->LOCATION_ID,
                    $SOURCE_REF_TYPE,
                    $this->DATE,
                    false
                );
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

            $buildAssembly      = (int) $this->buildAssemblyServices->object_type_build_assembly;
            $buildAssemblyItems = (int) $this->buildAssemblyServices->object_type_build_assembly_items;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($buildAssembly, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($buildAssembly, $this->ID) + 1;
            }

            //Main
            $buildAssemblyData = $this->buildAssemblyServices->getBuildAssemblyJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $buildAssemblyData,
                $this->LOCATION_ID,
                $buildAssembly,
                $this->DATE,
                "BUILD"
            );

            //Item
            $buildAssemblyItemData = $this->buildAssemblyServices->getBuildAssemblyItemsJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $buildAssemblyItemData,
                $this->LOCATION_ID,
                $buildAssemblyItems,
                $this->DATE,
                "COMPONENT"
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
    public function posted()
    {
        try {
            $count = (int) $this->buildAssemblyServices->CountItems($this->ID);
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }

            $checkItem = $this->buildAssemblyServices->ComponentList($this->ID, $this->LOCATION_ID);

            foreach ($checkItem as $list) {
                if ($list->OTY_OHAND < $list->QUANTITY) {
                    session()->flash('error', "Invalid. The total quantity for each item must be clearly indicated.");
                    return;

                }
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

            $this->buildAssemblyServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $data = $this->buildAssemblyServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->buildAssemblyServices->object_type_build_assembly, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }

    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->buildAssemblyServices->StatusUpdate($this->ID, 16);
            $this->removeJournal();
            DB::commit();
            Redirect::route('companybuild_assembly_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->buildAssemblyServices->object_type_build_assembly, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function render()
    {
        $this->updatedASSEMBLYITEMID();
        return view('livewire.build-assembly.build-assembly-form');
    }
}

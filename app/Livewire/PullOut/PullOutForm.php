<?php
namespace App\Livewire\PullOut;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\ItemInventoryServices;
use App\Services\LocationServices;
use App\Services\PullOutServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pull Out')]

class PullOutForm extends Component
{
    public int $ID;
    public int $openStatus = 0;
    public $accountList = [];
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $PREPARED_BY_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $ACCOUNT_ID;
    public $locationList = [];
    public $contactList = [];
    public bool $Modify;
    public bool $transferReset = false;
    private $pullOutServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $contactServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $accountServices;
    private $systemSettingServices;
    public function boot(
        PullOutServices $pullOutServices,
        AccountServices $accountServices,
        LocationServices $locationServices,
        DocumentStatusServices $documentStatusServices,
        ItemInventoryServices $itemInventoryServices,
        ContactServices $contactServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->pullOutServices = $pullOutServices;
        $this->locationServices = $locationServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->contactServices = $contactServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->accountServices = $accountServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function LoadDropdown()
    {
        $this->accountList = $this->accountServices->getCost();

        $this->locationList = $this->locationServices->getList();
        $this->contactList = $this->contactServices->getList(2);
    }

    private function ItemInventory(): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->pullOutServices->document_type_id;
            $data = $this->pullOutServices->ItemInventory($this->ID);

            if ($data) {
                $this->itemInventoryServices->InventoryExecute(
                    $data,
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
    // private function AccountJournal(): bool
    // {

    //     try {
    //         $pullOut = $this->pullOutServices->object_type_map_pull_out;
    //         $pullOutItems = $this->pullOutServices->object_type_map_pull_out_items;
    //         $JOURNAL_NO = $this->accountJournalServices->getRecord($pullOut, $this->ID);
    //         if ($JOURNAL_NO == 0) {
    //             $JOURNAL_NO = $this->accountJournalServices->getJournalNo($pullOut, $this->ID) + 1;
    //         }
    //         //Main
    //         $mainData = $this->pullOutServices->getPullOutJournal($this->ID);

    //         $this->accountJournalServices->JournalExecute(
    //             $JOURNAL_NO,
    //             $mainData,
    //             $this->LOCATION_ID,
    //             $pullOut,
    //             $this->DATE
    //         );
    //         //Item
    //         $itemData = $this->pullOutServices->getPullOutItemsJournal($this->ID);
    //         $this->accountJournalServices->JournalExecute(
    //             $JOURNAL_NO,
    //             $itemData,
    //             $this->LOCATION_ID,
    //             $pullOutItems,
    //             $this->DATE
    //         );

    //         $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

    //         $debit_sum = (float) $data['DEBIT'];
    //         $credit_sum = (float) $data['CREDIT'];

    //         if ($debit_sum == $credit_sum) {
    //             return true;
    //         }
    //         session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
    //         return false;
    //     } catch (\Exception $e) {
    //         $errorMessage = 'Error occurred: ' . $e->getMessage();
    //         session()->flash('error', $errorMessage);
    //         return false;
    //     }
    // }
    public function posted()
    {
        try {
            $count = (float) $this->pullOutServices->CountItems($this->ID);
            if ($count == 0) {
                Session()->flash('error', 'No item to transfer');
                return;
            }

            DB::beginTransaction();
            if (!$this->ItemInventory()) {
                DB::rollBack();
                return;
            }

            if (!$this->pullOutServices->getMakeJournal($this->ID)) {
                Session()->flash('error', 'Something wrong with journal entry');
                DB::rollBack();
                return;
            }
            $this->pullOutServices->StatusUpdate($this->ID, 15);
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
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->NOTES = $data->NOTES ?? '';
        $this->AMOUNT = $data->AMOUNT ?? 0;
        $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
        $this->ACCOUNT_ID = $data->ACCOUNT_ID ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->pullOutServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companypull_out')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->AMOUNT = 0;
        $this->PREPARED_BY_ID = 0;
        $this->NOTES = '';
        $this->ACCOUNT_ID = $this->pullOutServices->default_debit_account_id; // office supply expenses
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {

        if ($this->ID == 0) {

            $this->validate(
                [
                    'DATE' => 'required',
                    'LOCATION_ID' => 'required',
                    'PREPARED_BY_ID' => 'required|not_in:0',

                ],
                [],
                [
                    'DATE' => 'Date',
                    'LOCATION_ID' => 'Location',
                    'PREPARED_BY_ID' => 'Prepared by',
                ]
            );
        } else {
            $this->validate(
                [
                    'CODE' => 'required|max:20|unique:pull_out,code,' . $this->ID,
                    'DATE' => 'required',
                    'LOCATION_ID' => 'required',
                    'PREPARED_BY_ID' => 'required|not_in:0',
                ],
                [],
                [
                    'CODE' => 'Reference No.',
                    'DATE' => 'Date',
                    'LOCATION_ID' => 'Location',
                    'PREPARED_BY_ID' => 'Prepared by',
                ]
            );
        }

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }


        DB::beginTransaction();

        try {
            if ($this->ID == 0) {
                $this->ID = $this->pullOutServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->PREPARED_BY_ID,
                    $this->ACCOUNT_ID
                );
                DB::commit();
                return Redirect::route('companypull_out_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $data = $this->pullOutServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->pullOutServices->object_type_map_pull_out, $this->ID);
                        if ($JNO > 0) {
                            $this->accountJournalServices->AccountSwitch(
                                $this->ACCOUNT_ID,
                                $data->ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                0,
                                $this->ID,
                                $this->pullOutServices->object_type_map_pull_out,
                                $this->DATE,
                                0
                            );
                        }
                    }

                    $this->pullOutServices->Update(
                        $this->ID,
                        $this->CODE,
                        $this->NOTES,
                        $this->PREPARED_BY_ID,
                        $this->ACCOUNT_ID
                    );
                }
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

    public function updateCancel()
    {
        $BA = $this->pullOutServices->get($this->ID);
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

    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->pullOutServices->object_type_map_pull_out,
            $this->ID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }

    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->pullOutServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('companypull_out_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.pull-out.pull-out-form');
    }
}

<?php
namespace App\Livewire\Depreciation;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DepreciationServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Depreciation Form')]

class DepreciationForm extends Component
{
    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $LOCATION_ID;
    public int $DEPRECIATION_ACCOUNT_ID;
    public string $NOTES;
    public bool $IS_AUTO;
    public float $AMOUNT;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public $accountList  = [];
    public $locationList = [];

    public bool $Modify = false;
    private $depreciationServices;
    private $userServices;
    private $locationServices;
    private $accountServices;
    private $documentStatusServices;
    private $accountJournalServices;
    public function boot(
        DepreciationServices $depreciationServices,
        UserServices $userServices,
        LocationServices $locationServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices
    ) {

        $this->depreciationServices   = $depreciationServices;
        $this->userServices           = $userServices;
        $this->locationServices       = $locationServices;
        $this->accountServices        = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount($id = null)
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList  = $this->accountServices->getAccount(false);
        if (is_numeric($id)) {
            $data = $this->depreciationServices->Get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            return Redirect::route('companydepreciation')->with('error', 'Record not found');
        }

        $this->ID                      = 0;
        $this->CODE                    = '';
        $this->DATE                    = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID             = $this->userServices->getLocationDefault();
        $this->DEPRECIATION_ACCOUNT_ID = $this->depreciationServices->DEPRECIATION_ACCOUNT_ID;
        $this->NOTES                   = '';
        $this->IS_AUTO                 = false;
        $this->AMOUNT                  = 0;
        $this->STATUS                  = 0;
        $this->Modify                  = true;
        $this->STATUS_DESCRIPTION      = "";
    }
    public function save()
    {

        $this->validate([
            'CODE'                    => $this->ID > 0 ? 'required|max:20|unique:depreciation,code,' . $this->ID : 'nullable',
            'DATE'                    => 'required|date',
            'LOCATION_ID'             => 'required|exists:location,id',
            'DEPRECIATION_ACCOUNT_ID' => 'required|numeric|exists:account,id',
        ]);

        try {
            if ($this->ID == 0) {
                $this->ID = (int) $this->depreciationServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->DEPRECIATION_ACCOUNT_ID,
                    $this->NOTES,
                    $this->IS_AUTO
                );

                return Redirect::route('companydepreciation_edit', ['id' => $this->ID]);
            }

            $this->depreciationServices->Update(
                $this->ID,
                $this->CODE,
                $this->DEPRECIATION_ACCOUNT_ID,
                $this->NOTES
            );
        } catch (\Exception $ex) {
            $errorMessage = 'Error occurred: ' . $ex->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function getInfo($data)
    {

        $this->ID                      = $data->ID ?? 0;
        $this->CODE                    = $data->CODE ?? '';
        $this->DATE                    = $data->DATE ?? '';
        $this->LOCATION_ID             = $data->LOCATION_ID ?? 0;
        $this->DEPRECIATION_ACCOUNT_ID = $data->DEPRECIATION_ACCOUNT_ID ?? 0;
        $this->NOTES                   = $data->NOTES ?? '';
        $this->IS_AUTO                 = $data->IS_AUTO ?? false;
        $this->AMOUNT                  = $data->AMOUNT ?? 0;
        $this->STATUS                  = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION      = $this->documentStatusServices->getDesc($this->STATUS);
    }

    #[On('refresh-amount')]
    public function getTotal()
    {
        $data = $this->depreciationServices->Get($this->ID);
        if ($data) {
            $this->AMOUNT = $data->AMOUNT ?? 0;
            return;
        }

        $this->AMOUNT = 0;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    public function getModify()
    {
        $this->Modify = true;
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->depreciationServices->StatusUpdate($this->ID, 16);

            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depreciationServices->object_type_depreciation, $this->ID);
            if ($JOURNAL_NO > 0) {
                $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
            }
            DB::commit();
            Redirect::route('companydepreciation_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function OpenJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->depreciationServices->object_type_depreciation, $this->ID);

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    public function getPosted()
    {
        try {
            DB::beginTransaction();
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depreciationServices->object_type_depreciation, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->depreciationServices->object_type_depreciation, $this->ID) + 1;
            }

            $depreciationData = $this->depreciationServices->DepreciationJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $depreciationData,
                $this->LOCATION_ID,
                $this->depreciationServices->object_type_depreciation,
                $this->DATE,
                ""
            );

            $depreciationItemData = $this->depreciationServices->DepreciationItemJournal($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $depreciationItemData,
                $this->LOCATION_ID,
                $this->depreciationServices->object_type_depreciation_item,
                $this->DATE,
                "ASSET"
            );

            $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                $this->depreciationServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->depreciationServices->Get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                session()->flash('message', 'Successfully posted');
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getDelete()
    {
        DB::beginTransaction();
        try {
            //code...
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depreciationServices->object_type_depreciation, $this->ID);
            if ($JOURNAL_NO > 0) {
                $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
            }

            $this->depreciationServices->Delete($this->ID);
            DB::commit();

            return Redirect::route('companydepreciation')->with('message', 'File successfully deleted');
        } catch (\Throwable $e) {
            //throw $th;

            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);

        }

    }
    public function updateCancel()
    {
        Redirect::route('companydepreciation_edit', $this->ID);
    }
    public function render()
    {
        return view('livewire.depreciation.depreciation-form');
    }
}

<?php

namespace App\Livewire\SpendMoney;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\SpendMoneyServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Spend Money')]
class SpendMoneyForm extends Component
{
    public $locationList = [];
    public $accountList = [];
    public bool $Modify = false;
    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ACCOUNT_ID;
    public string $NOTES;
    public int $STATUS;
    public float $AMOUNT = 0;
    public string $STATUS_DESCRIPTION;
    private $spendMoneyServices;
    private $locationServices;
    private $accountServices;
    private $userServices;
    private $dateServices;
    private $documentStatusServices;
    private $accountJournalServices;
    public function boot(
        SpendMoneyServices $spendMoneyServices,
        AccountServices $accountServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->spendMoneyServices = $spendMoneyServices;
        $this->locationServices = $locationServices;
        $this->accountServices = $accountServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    private function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        if ($id != null) {
            $data = $this->spendMoneyServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);

                $this->Modify = false;
                return;
            }
        }
        $this->LoadDropdown();
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->ACCOUNT_ID = 0;
        $this->NOTES = '';
        $this->CODE = '';
        $this->AMOUNT = 0;
        $this->Modify = true;
        $this->ID = 0;
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
        // Initialize any properties or perform actions when the component is mounted
    }
    public function save()
    {
        $this->validate(
            [
                'DATE' => 'required|date_format:Y-m-d',
                'LOCATION_ID' => 'required|numeric|exists:location,id',
                'ACCOUNT_ID' => 'required|numeric|exists:account,id',
                'NOTES' => 'nullable|string|max:255',
            ],
            [],
            [
                'DATE' => 'Date',
                'LOCATION_ID' => 'Location',
                'ACCOUNT_ID' => 'Account',
                'NOTES' => 'Notes',
            ]
        );

        try {
            if ($this->ID > 0) {
                $this->spendMoneyServices->Update($this->ID, $this->DATE, $this->CODE, $this->LOCATION_ID, $this->ACCOUNT_ID, $this->NOTES);
                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            } else {
                $this->ID = $this->spendMoneyServices->Store($this->DATE, $this->CODE, $this->LOCATION_ID, $this->ACCOUNT_ID, $this->NOTES);
                return Redirect::route('bankingspend_money_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);

        }
    }

    public function getModify()
    {
        $this->Modify = true;

    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->DATE = $data->DATE;
        $this->CODE = $data->CODE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->ACCOUNT_ID = $data->ACCOUNT_ID;
        $this->NOTES = $data->NOTES;
        $this->STATUS = $data->STATUS;
        $this->AMOUNT = $data->AMOUNT;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    private function AccountJournal(): bool
    {
        try {


            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->spendMoneyServices->object_type_map_spend_money, $this->ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->spendMoneyServices->object_type_map_spend_money, $this->ID) + 1;
            }

            //Main
            $main = $this->spendMoneyServices->JournalEntry($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $main,
                $this->LOCATION_ID,
                $this->spendMoneyServices->object_type_map_spend_money,
                $this->DATE
            );

            //Details
            $details = $this->spendMoneyServices->JournalEntryDetails($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $details,
                $this->LOCATION_ID,
                $this->spendMoneyServices->object_type_map_spend_money_details,
                $this->DATE
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
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
            $detailsList = $this->spendMoneyServices->getDetailsList($this->ID);

            if ($detailsList) {
                DB::beginTransaction();
                if (!$this->AccountJournal()) {
                    DB::rollBack();
                    return;
                }
                $this->spendMoneyServices->StatusUpdate($this->ID, 15);
                DB::commit();

                $data = $this->spendMoneyServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                }

                Session()->flash('message', 'Successfully posted');
                return;
            }

            Session()->flash('error', 'Invalid Entry.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->spendMoneyServices->object_type_map_spend_money,
            $this->ID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }
    public function render()
    {
        return view('livewire.spend-money.spend-money-form');
    }
}

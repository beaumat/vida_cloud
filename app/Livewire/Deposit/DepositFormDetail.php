<?php

namespace App\Livewire\Deposit;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DepositServices;
use App\Services\PaymentMethodServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DepositFormDetail extends Component
{
    #[Reactive]
    public int $DEPOSIT_ID;
    #[Reactive]
    public int $STATUS;
    public int $RECEIVED_FROM_ID;
    public int $ACCOUNT_ID;
    public int $PAYMENT_METHOD_ID;
    public string $CHECK_NO;
    public float $AMOUNT;
    public int $SOURCE_OBJECT_TYPE;
    public int $SOURCE_OBJECT_ID;
    public bool $saveSuccess = false;
    public $dataList = [];
    public $contactList = [];
    public $accountList = [];
    public $paymentMethodList = [];

    private $depositServices;
    private $contactServices;
    private $accountServices;
    private $paymentMethodServices;
    private $accountJournalServices;
    public function boot(
        DepositServices $depositServices,
        ContactServices $contactServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->depositServices = $depositServices;
        $this->contactServices = $contactServices;
        $this->accountServices = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function LoadDropDown()
    {
        $this->accountList = $this->accountServices->getAccount(false);
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
        $this->contactList = $this->contactServices->getListAllType();
    }
    public function mount()
    {
        $this->clearField();
        $this->LoadDropDown();
    }
    public function clearField()
    {
        $this->RECEIVED_FROM_ID = 0;
        $this->PAYMENT_METHOD_ID = 0;
        $this->ACCOUNT_ID = 0;
        $this->AMOUNT = 0;
        $this->CHECK_NO = '';
        $this->saveSuccess = $this->saveSuccess ? false : true;
    }
    public function AddFund()
    {
        $this->validate([
            'ACCOUNT_ID' => 'required|exists:account,id',
            'AMOUNT'     => 'required|numeric|not_in:0',
        ], [], [
            'ACCOUNT_ID'    => 'Account',
            'AMOUNT'        => 'Amount'
        ]);

        DB::beginTransaction();
        try {
            $this->depositServices->StoreFund(
                $this->DEPOSIT_ID,
                $this->RECEIVED_FROM_ID,
                $this->ACCOUNT_ID,
                $this->PAYMENT_METHOD_ID,
                $this->CHECK_NO,
                $this->AMOUNT,
                0,
                0
            );

            $this->depositServices->UpdateAmount($this->DEPOSIT_ID);
            DB::commit();
            $this->dispatch('get-amount');
            $this->clearField();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public $editFundId = null;
    public int $editAccountId  = 0;
    public float $editAmount = 0;
    public int $editReceivedFromId = 0;
    public int $editPaymentMethodId = 0;
    public string $editCheckNo = '';

    public function EditFund(int $id)
    {
        $data =    $this->depositServices->GetFund($id);
        if ($data) {
            $this->editFundId = $data->ID;
            $this->editReceivedFromId = $data->RECEIVED_FROM_ID ?? 0;
            $this->editAccountId = $data->ACCOUNT_ID ?? 0;
            $this->editPaymentMethodId = $data->PAYMENT_METHOD_ID ?? 0;
            $this->editCheckNo = $data->CHECK_NO ?? '';
            $this->editAmount = $data->AMOUNT ?? 0;
        }
    }

    public function UpdateFund()
    {

        $this->validate([
            'editAccountId' => 'required|exists:account,id',
            'editAmount'     => 'required|numeric|not_in:0',
        ], [], [
            'editAccountId'    => 'Account',
            'editAmount'        => 'Amount'
        ]);


        DB::beginTransaction();
        try {
            $this->depositServices->UpdateFund(
                $this->editFundId,
                $this->DEPOSIT_ID,
                $this->editReceivedFromId,
                $this->editAccountId,
                $this->editPaymentMethodId,
                $this->editCheckNo,
                $this->editAmount,

            );
            $this->depositServices->UpdateAmount($this->DEPOSIT_ID);
            DB::commit();
            $this->dispatch('get-amount');
            $this->CancelFund();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function DeleteFund(int $ID)
    {

        DB::beginTransaction();
        try {
            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->depositServices->object_type_deposit_fund,
                    $ID
                );

                if ($JOURNAL_NO  >  0) {
                    $gData = $this->depositServices->get($this->DEPOSIT_ID);
                    if ($gData) {
                        $gDetails = $this->depositServices->GetFund($ID);
                        if ($gDetails) {
                            // ACCOUNT_ID
                            $this->accountJournalServices->DeleteJournal(
                                $gDetails->ACCOUNT_ID,
                                $gData->LOCATION_ID,
                                $JOURNAL_NO,
                                0,
                                $ID,
                                $this->depositServices->object_type_deposit_fund,
                                $gData->DATE,
                                $gDetails->ENTRY_TYPE
                            );
                        }
                    }
                }
            }

            $data = $this->depositServices->GetFund($ID);
            if ($data) {
                if ($data->SOURCE_OBJECT_ID > 0) {
                    $this->depositServices->UndepositedUpdate($data->SOURCE_OBJECT_ID, $data->SOURCE_OBJECT_TYPE, 0);
                }

                $this->depositServices->DeleteFund($ID, $this->DEPOSIT_ID);
                $this->depositServices->UpdateAmount($this->DEPOSIT_ID);
                DB::commit();
                $this->dispatch('get-amount');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function CancelFund()
    {
        $this->editFundId = null;
        $this->editReceivedFromId = 0;
        $this->editAccountId = 0;
        $this->editPaymentMethodId = 0;
        $this->editCheckNo = '';
        $this->editAmount =  0;
    }
    public function render()
    {
        $this->dataList = $this->depositServices->FundList($this->DEPOSIT_ID);
        return view('livewire.deposit.deposit-form-detail');
    }
}

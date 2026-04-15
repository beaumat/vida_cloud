<?php

namespace App\Livewire\BillPayments;

use App\Services\AccountJournalServices;
use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use App\Services\LocationServices;
use App\Services\TaxServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillList extends Component
{
    #[Reactive]
    public int $CHECK_ID;
    public $dataList = [];
    public int $openStatus;
    private $billPaymentServices;
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public bool $SAME_AMOUNT;
    #[Reactive]
    public int $PF_PERIOD_ID;
    #[Reactive()]
    public string $DATE;



    public float $BILL_PAID = 0;
    public int $EWT_ID = 10;
    public $EWT_ACCOUNT_ID = 29;
    public float $AMOUNT_WITHHELD;
    public float $EWT_RATE;


    public float $prevAmount;
    public float $orgAmount;
    public $editPaymentId = null;
    public int $editBill_Id;
    public float $editAmountApplied;

    private $billingServices;
    private $accountJournalServices;
    private $withholdingTaxServices;
    private $taxServices;
    private $locationServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        BillingServices $billingServices,
        AccountJournalServices $accountJournalServices,
        WithholdingTaxServices $withholdingTaxServices,
        TaxServices $taxServices,
        LocationServices $locationServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->billingServices = $billingServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->taxServices = $taxServices;
        $this->locationServices = $locationServices;

    }
    public function edit(int $ID, int $BILL_ID, float $Applied)
    {
        $this->editPaymentId = $ID;
        $this->editBill_Id = $BILL_ID;
        $this->editAmountApplied = $Applied;
        $this->prevAmount = $Applied;
        $data = $this->billingServices->get($BILL_ID);
        if ($data) {
            $this->orgAmount = $data->AMOUNT;
        }
    }
    public function cancel()
    {
        $this->editPaymentId = null;
    }
    public function update()
    {
        $RemainAmount = (float) $this->AMOUNT_APPLIED - $this->prevAmount;
        if ($this->AMOUNT < ($RemainAmount + $this->editAmountApplied)) {
            session()->flash('error', 'Invalid payment initial. the remaining bill payment to low.');
            return;
        }

        $totalPay = (float) $this->billPaymentServices->getTotalPay($this->editBill_Id, $this->CHECK_ID);
        $current_balance = (float) $this->orgAmount - $totalPay;
        if ($current_balance < $this->editAmountApplied) {
            session()->flash('error', 'invalid bill payment initial is to high from billing balance. please enter exactly initial amount');
            return;
        }
        $this->billPaymentServices->billPaymentBills_Update($this->editPaymentId, $this->CHECK_ID, $this->editBill_Id, 0, $this->editAmountApplied);
        $this->billingServices->UpdateBalance($this->editBill_Id);
        $this->editPaymentId = null;
        $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $BILL_ID)
    {
        try {
            DB::beginTransaction();
            if ($this->STATUS == 16) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $this->CHECK_ID);
                if ($JOURNAL_NO == 0) {
                    session()->flash('message', 'journal not found');
                    return;
                }
                $checkData = $this->billPaymentServices->get($this->CHECK_ID);
                if ($checkData) {

                    $billCheckBills = $this->billPaymentServices->billPaymentBills_Get($ID, $this->CHECK_ID, $BILL_ID);
                    if ($billCheckBills) {
                        $this->accountJournalServices->DeleteJournal(
                            $billCheckBills->ACCOUNTS_PAYABLE_ID,
                            $checkData->LOCATION_ID,
                            $JOURNAL_NO,
                            $checkData->PAY_TO_ID,
                            $ID,
                            $this->billPaymentServices->object_type_check_bills,
                            $checkData->DATE,
                            0
                        );
                    }
                }
            }

            $this->billPaymentServices->billPaymentBills_Delete($ID, $this->CHECK_ID, $BILL_ID);

            $this->billingServices->UpdateBalance($BILL_ID);
            DB::commit();
            $this->TaxMustDeleteToo($BILL_ID);
            $this->SetAmount();
            $this->dispatch('reset-payment');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function TaxMustDeleteToo(int $BILL_ID)
    {

        if ($this->PF_PERIOD_ID > 0) {
            // dapat meron  numero para mag hook sa auto tax
            $WTAX_ID = $this->withholdingTaxServices->GetID($BILL_ID);
            if ($WTAX_ID > 0) {
                $this->deleteWTax($WTAX_ID);
            }
        }


    }

    private function deleteJournalTax($data, int $id)
    {

        $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->withholdingTaxServices->object_type_withholding_tax_id, $id);

        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->DeleteJournal(
                $data->EWT_ACCOUNT_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->WITHHELD_FROM_ID,
                $data->ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $data->DATE,
                1
            );
            $billListData = $this->withholdingTaxServices->GetBillList($id);
            foreach ($billListData as $list) {
                $this->accountJournalServices->DeleteJournal(
                    $list->ACCOUNTS_PAYABLE_ID,
                    $data->LOCATION_ID,
                    $JOURNAL_NO,
                    $list->BILL_ID,
                    $list->ID,
                    $this->withholdingTaxServices->object_type_witholding_tax_bills_id,
                    $data->DATE,
                    0
                );
            }

            // optional if remaining
            $this->accountJournalServices->DeleteJournal(
                $data->ACCOUNTS_PAYABLE_ID,
                $data->LOCATION_ID,
                $JOURNAL_NO,
                $data->WITHHELD_FROM_ID,
                $data->ID,
                $this->withholdingTaxServices->object_type_withholding_tax_id,
                $data->DATE,
                0
            );
        }

    }
    private function deleteWTax(int $ID)
    {
        try {
            $data = $this->withholdingTaxServices->Get($ID);
            if ($data) {
                if ($data->STATUS == 0 || $data->STATUS == 15 || $data->STATUS == 16) {
                    DB::beginTransaction();
                    try {
                        $this->deleteJournalTax($data, $ID);
                        $billList = $this->withholdingTaxServices->GetBillList($ID);
                        $this->withholdingTaxServices->Delete($ID);
                        foreach ($billList as $list) {
                            $this->billingServices->UpdateBalance($list->BILL_ID);
                        }
                        DB::commit();

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $errorMessage = 'Error occurred: ' . $e->getMessage();
                        session()->flash('error', $errorMessage);
                    }
                    return;
                }

                session()->flash('error', 'Invalid. this file cannot be deleted.');
            }
        } catch (\Throwable $th) {

            session()->flash('error', 'Error:' . $th->getMessage());
        }

    }
    private function SetAmount()
    {
        $AMOUNT = (float) $this->billPaymentServices->getTotalApplied($this->CHECK_ID);
        $this->billPaymentServices->UpdateAmount($this->CHECK_ID, $AMOUNT);
    }
    public function mount(int $CHECK_ID, int $VENDOR_ID, int $LOCATION_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->CHECK_ID = $CHECK_ID;
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
        $locData = $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            $this->EWT_ID = $locData->PF_TAX_ID ?? 10;
        }
    }

    public function getSetTax(float $BALANCE_DUE)
    {

        $tax = $this->taxServices->get($this->EWT_ID);
        if ($tax) {
            $this->EWT_RATE = $tax->RATE ?? 0;
            $this->AMOUNT_WITHHELD = $BALANCE_DUE * ($this->EWT_RATE / 100);
            $this->EWT_ACCOUNT_ID = $tax->TAX_ACCOUNT_ID ?? 0;
            $this->BILL_PAID = $BALANCE_DUE - $this->AMOUNT_WITHHELD;
        }



    }
    public function addingTax(int $ID, int $BILL_ID, float $AMOUNT)
    {
        if ($this->withholdingTaxServices->BillExists($BILL_ID)) {
            session()->flash('error', 'Bill already has withholding tax.');
            return;
        }

        DB::beginTransaction();

        try {
            $isGood = (bool) $this->addTax($BILL_ID, $AMOUNT);
            if (!$isGood) {
                DB::rollBack();
                return;
            }
            $this->billPaymentServices->billPaymentBills_Update($ID, $this->CHECK_ID, $BILL_ID, 0, $this->BILL_PAID);
            $this->billingServices->UpdateBalance($BILL_ID);
            $this->SetAmount();
            $this->dispatch('reload_bill_list');
            DB::commit();

        } catch (\Throwable $th) {
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
            DB::rollBack();
        }
    }
    public function addTax(int $BILL_ID, float $AMOUNT)
    {


        if ($this->EWT_ID == 0) {
            return false;
        }



        $this->getSetTax($AMOUNT);

        $ID = $this->withholdingTaxServices->Store(
            "",
            $this->DATE,
            $this->VENDOR_ID,
            $this->EWT_RATE,
            $this->EWT_ID,
            $this->EWT_ACCOUNT_ID,
            $this->LOCATION_ID,
            '',
            $this->billingServices->ACCOUNTS_PAYABLE_ID
        );


        $this->withholdingTaxServices->StoreBill(
            $ID,
            $BILL_ID,
            $this->AMOUNT_WITHHELD,
            $this->billingServices->ACCOUNTS_PAYABLE_ID
        );

        $total = $this->withholdingTaxServices->GetTotal($ID);
        $this->withholdingTaxServices->setTotal($ID, $total);
        $this->billingServices->UpdateBalance($BILL_ID);

        $isGood = $this->withholdingTaxServices->getPosted($ID, $this->DATE, $this->LOCATION_ID);

        return $isGood;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    #[On('reload_bill_list')]
    public function render()
    {
        $this->dataList = $this->billPaymentServices->billPaymentBills($this->CHECK_ID);

        return view('livewire.bill-payments.bill-list');
    }
}

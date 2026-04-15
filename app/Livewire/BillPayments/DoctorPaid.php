<?php

namespace App\Livewire\BillPayments;

use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use App\Services\LocationServices;
use App\Services\TaxServices;
use App\Services\WithholdingTaxServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorPaid extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $CHECK_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    #[Reactive]
    public bool $SAME_AMOUNT;
    #[Reactive]
    public int $PF_PERIOD_ID;

    #[Reactive]
    public string $DATE;

    public float $BILL_PAID = 0;
    public int $EWT_ID = 10;
    public $EWT_ACCOUNT_ID = 29;
    public float $AMOUNT_WITHHELD;
    public float $EWT_RATE;
    public $invoiceList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $billingServices;
    private $billPaymentServices;
    private $withholdingTaxServices;
    private $taxServices;
    private $locationServices;
    public function boot(
        BillingServices $billingServices,
        BillPaymentServices $billPaymentServices,
        WithholdingTaxServices $withholdingTaxServices,
        TaxServices $taxServices,
        LocationServices $locationServices
    ) {
        $this->billingServices = $billingServices;
        $this->billPaymentServices = $billPaymentServices;
        $this->withholdingTaxServices = $withholdingTaxServices;
        $this->taxServices = $taxServices;
        $this->locationServices = $locationServices;
    }
    public function mount(int $VENDOR_ID, int $LOCATION_ID, int $CHECK_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->CHECK_ID = $CHECK_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }

    public function openModal()
    {

        $locData = $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            $this->EWT_ID = $locData->PF_TAX_ID ?? 10;

         
        }
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function toPaid(int $BILL_ID)
    {
        $ID = (int) $this->billPaymentServices->BillPaymentBillsExist($this->CHECK_ID, $BILL_ID); // if already added
        if ($ID > 0) {
            $this->billPaymentServices->billPaymentBills_Update($ID, $this->CHECK_ID, $BILL_ID, 0, $this->BILL_PAID);
        } else {
            $bill = $this->billingServices->get($BILL_ID);
            if ($bill) {
                $this->billPaymentServices->billPaymentBills_Store($this->CHECK_ID, $BILL_ID, 0, $this->BILL_PAID, 0, $bill->ACCOUNTS_PAYABLE_ID ?? 0);
            }
            $AMOUNT = (float) $this->billPaymentServices->getTotalApplied($this->CHECK_ID);
            $this->billPaymentServices->UpdateAmount($this->CHECK_ID, $AMOUNT);
        }
        $this->billingServices->UpdateBalance($BILL_ID);
        $this->dispatch('reset-payment');

    }


    public function addItem(int $BILL_ID, float $BILL_AMOUNT)
    {

        DB::beginTransaction();
        try {

            $isBill = $this->AddTAX($BILL_ID, $BILL_AMOUNT);
            if (!$isBill) {
                DB::rollBack();
            }


            $this->toPaid($BILL_ID);
            DB::commit();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollBack();
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
    public function AddTAX(int $BILL_ID, float $AMOUNT)
    {
        if ($this->EWT_ID == 0) {
            return true;
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
    public function render()
    {

        $this->invoiceList = $this->billingServices->getBillListViaBillPaymentExistOnPhilealth($this->VENDOR_ID, $this->LOCATION_ID, $this->CHECK_ID, $this->PF_PERIOD_ID);

        return view('livewire.bill-payments.doctor-paid');
    }
}
